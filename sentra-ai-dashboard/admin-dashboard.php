<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header Banner -->
        <div class="relative h-48 bg-gradient-to-r from-blue-600 to-indigo-700">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="relative z-10 px-8 py-12">
                <h1 class="text-3xl font-bold text-white mb-2">Sentra AI Dashboard</h1>
                <p class="text-gray-200">Manage your AI training data, company information, and Q&A settings</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" aria-label="Tabs">
                <button class="tab-button active w-1/3 py-4 px-6 text-center border-b-2 border-blue-500 font-medium text-blue-600" data-tab="training">
                    <i class="fas fa-brain mr-2"></i>Training Data
                </button>
                <button class="tab-button w-1/3 py-4 px-6 text-center border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="company">
                    <i class="fas fa-building mr-2"></i>Company Info
                </button>
                <button class="tab-button w-1/3 py-4 px-6 text-center border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="qna">
                    <i class="fas fa-question-circle mr-2"></i>Q&A
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Training Data Tab -->
            <div id="training-tab" class="tab-content active">
                <form id="training-form" class="space-y-6">
                    <?php wp_nonce_field('sentra_ai_training_nonce', 'training_nonce'); ?>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="training-title">
                                Title
                            </label>
                            <input type="text" id="training-title" name="title" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="training-description">
                                Description
                            </label>
                            <textarea id="training-description" name="description" rows="3" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="training-data">
                                Training Data
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, TXT, CSV up to 10MB</p>
                                </div>
                            </div>
                            <textarea id="training-data" name="data" rows="5"
                                class="mt-4 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Or enter your training data here..."></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>Save Training Data
                        </button>
                    </div>
                </form>
            </div>

            <!-- Company Info Tab -->
            <div id="company-tab" class="tab-content hidden">
                <form id="company-form" class="space-y-6">
                    <?php wp_nonce_field('sentra_ai_company_nonce', 'company_nonce'); ?>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="company-name">
                                Company Name
                            </label>
                            <input type="text" id="company-name" name="company_name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="company-address">
                                Address
                            </label>
                            <textarea id="company-address" name="address" rows="3" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="company-email">
                                    Contact Email
                                </label>
                                <input type="email" id="company-email" name="email" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="company-phone">
                                    Phone Number
                                </label>
                                <input type="tel" id="company-phone" name="phone" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- API Configuration Section (Read-only) -->
                        <div class="mt-8 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">API Configuration</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="whatsapp-api">
                                        WhatsApp API Key
                                    </label>
                                    <input type="text" id="whatsapp-api" readonly
                                        class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 text-gray-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="newoaks-api">
                                        Newoaks API Key
                                    </label>
                                    <input type="text" id="newoaks-api" readonly
                                        class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 text-gray-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>Save Company Info
                        </button>
                    </div>
                </form>
            </div>

            <!-- Q&A Tab -->
            <div id="qna-tab" class="tab-content hidden">
                <form id="qna-form" class="space-y-6">
                    <?php wp_nonce_field('sentra_ai_qna_nonce', 'qna_nonce'); ?>
                    
                    <div id="qna-entries" class="space-y-6">
                        <!-- Initial Q&A Entry -->
                        <div class="qna-entry bg-gray-50 p-4 rounded-lg">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Question</label>
                                    <input type="text" name="questions[]" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Answer</label>
                                    <textarea name="answers[]" rows="3" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="button" class="remove-qna text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" id="add-qna" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i>Add Another Q&A
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>Save Q&A
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>