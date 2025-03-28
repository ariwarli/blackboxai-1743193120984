<?php
/**
 * Google Sheets API Integration
 * 
 * Handles all interactions with Google Sheets for the Sentra AI Dashboard
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Sentra_AI_Sheets_API {
    private static $instance = null;
    private $spreadsheet_id;
    private $client;

    /**
     * Constructor
     */
    private function __construct() {
        // Get the spreadsheet ID from WordPress options
        $this->spreadsheet_id = get_option('sentra_ai_spreadsheet_id', '1L7Lxmdh0i_NvGo1D2p3ylcRaUeWqET8asIlH5QU8q64');
        
        // Initialize Google Sheets API client
        $this->init_client();
    }

    /**
     * Singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize Google Sheets API client
     */
    private function init_client() {
        // Get credentials from WordPress options
        $credentials = get_option('sentra_ai_google_credentials', '');
        
        if (empty($credentials)) {
            error_log('Google Sheets API credentials not found');
            return;
        }

        try {
            require_once SENTRA_AI_PLUGIN_DIR . 'vendor/autoload.php';

            $this->client = new Google_Client();
            $this->client->setAuthConfig(json_decode($credentials, true));
            $this->client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        } catch (Exception $e) {
            error_log('Google Sheets API client initialization failed: ' . $e->getMessage());
        }
    }

    /**
     * Save training data to Google Sheets
     */
    public function save_training_data($data) {
        try {
            $service = new Google_Service_Sheets($this->client);
            
            // Prepare the data for insertion
            $values = [
                [
                    date('Y-m-d H:i:s'),  // Timestamp
                    $data['title'],
                    $data['description'],
                    $data['data']
                ]
            ];

            $body = new Google_Service_Sheets_ValueRange([
                'values' => $values
            ]);

            // Append to the TrainingData sheet
            $result = $service->spreadsheets_values->append(
                $this->spreadsheet_id,
                'TrainingData!A:D',
                $body,
                ['valueInputOption' => 'RAW']
            );

            return [
                'success' => true,
                'message' => 'Training data saved successfully'
            ];
        } catch (Exception $e) {
            error_log('Error saving training data: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error saving training data'
            ];
        }
    }

    /**
     * Save company information to Google Sheets
     */
    public function save_company_info($data) {
        try {
            $service = new Google_Service_Sheets($this->client);
            
            // Prepare the data for insertion
            $values = [
                [
                    date('Y-m-d H:i:s'),  // Timestamp
                    $data['company_name'],
                    $data['address'],
                    $data['email'],
                    $data['phone']
                ]
            ];

            $body = new Google_Service_Sheets_ValueRange([
                'values' => $values
            ]);

            // Update the CompanyInfo sheet
            $result = $service->spreadsheets_values->update(
                $this->spreadsheet_id,
                'CompanyInfo!A2:E2',  // Assuming we always update the first row after headers
                $body,
                ['valueInputOption' => 'RAW']
            );

            return [
                'success' => true,
                'message' => 'Company information saved successfully'
            ];
        } catch (Exception $e) {
            error_log('Error saving company info: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error saving company information'
            ];
        }
    }

    /**
     * Save Q&A data to Google Sheets
     */
    public function save_qna_data($data) {
        try {
            $service = new Google_Service_Sheets($this->client);
            
            // Prepare the data for insertion
            $values = [];
            foreach ($data['questions'] as $index => $question) {
                $values[] = [
                    date('Y-m-d H:i:s'),  // Timestamp
                    $question,
                    $data['answers'][$index]
                ];
            }

            $body = new Google_Service_Sheets_ValueRange([
                'values' => $values
            ]);

            // Append to the QnA sheet
            $result = $service->spreadsheets_values->append(
                $this->spreadsheet_id,
                'QnA!A:C',
                $body,
                ['valueInputOption' => 'RAW']
            );

            return [
                'success' => true,
                'message' => 'Q&A data saved successfully'
            ];
        } catch (Exception $e) {
            error_log('Error saving QnA data: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error saving Q&A data'
            ];
        }
    }

    /**
     * Get existing data from Google Sheets
     */
    public function get_sheet_data($sheet_name, $range) {
        try {
            $service = new Google_Service_Sheets($this->client);
            
            $result = $service->spreadsheets_values->get(
                $this->spreadsheet_id,
                $sheet_name . '!' . $range
            );

            return [
                'success' => true,
                'data' => $result->getValues()
            ];
        } catch (Exception $e) {
            error_log('Error getting sheet data: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error retrieving data from Google Sheets'
            ];
        }
    }

    /**
     * Validate Google Sheets connection
     */
    public function validate_connection() {
        try {
            $service = new Google_Service_Sheets($this->client);
            
            // Try to get spreadsheet metadata
            $spreadsheet = $service->spreadsheets->get($this->spreadsheet_id);
            
            return [
                'success' => true,
                'message' => 'Successfully connected to Google Sheets'
            ];
        } catch (Exception $e) {
            error_log('Google Sheets connection validation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to connect to Google Sheets'
            ];
        }
    }
}

// Initialize AJAX handlers
function sentra_ai_save_training_data() {
    check_ajax_referer('sentra_ai_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized access');
    }

    $data = $_POST['formData'];
    $sheets_api = Sentra_AI_Sheets_API::get_instance();
    $result = $sheets_api->save_training_data($data);
    
    wp_send_json($result);
}
add_action('wp_ajax_save_training_data', 'sentra_ai_save_training_data');

function sentra_ai_save_company_info() {
    check_ajax_referer('sentra_ai_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized access');
    }

    $data = $_POST['formData'];
    $sheets_api = Sentra_AI_Sheets_API::get_instance();
    $result = $sheets_api->save_company_info($data);
    
    wp_send_json($result);
}
add_action('wp_ajax_save_company_info', 'sentra_ai_save_company_info');

function sentra_ai_save_qna_data() {
    check_ajax_referer('sentra_ai_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized access');
    }

    $data = $_POST['formData'];
    $sheets_api = Sentra_AI_Sheets_API::get_instance();
    $result = $sheets_api->save_qna_data($data);
    
    wp_send_json($result);
}
add_action('wp_ajax_save_qna_data', 'sentra_ai_save_qna_data');