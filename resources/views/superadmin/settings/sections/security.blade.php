<x-settings.settings-card 
    title="Security Settings" 
    description="Configure password policies and security preferences"
    icon="fas fa-shield-alt"
>
    <div class="space-y-6">
        <div class="space-y-4">
            <h4 class="text-sm font-medium text-white">Password Policy</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-settings.form-input 
                    type="number"
                    label="Minimum Password Length"
                    model="settings.security.min_password_length"
                    :min="6"
                    :max="32"
                    description="Minimum number of characters required"
                />
                <x-settings.form-input 
                    type="select"
                    label="Auto Logout Time"
                    model="settings.security.auto_logout"
                    :options="[
                        '15' => '15 minutes',
                        '30' => '30 minutes',
                        '60' => '1 hour',
                        '120' => '2 hours'
                    ]"
                />
            </div>
        </div>

        <div class="space-y-4">
            <h4 class="text-sm font-medium text-white">Security Features</h4>
            <div class="space-y-3">
                <x-settings.toggle-switch 
                    title="Two-Factor Authentication"
                    description="Require 2FA for all admin accounts"
                    model="settings.security.two_factor_enabled"
                />
                <x-settings.toggle-switch 
                    title="Password Reset Required"
                    description="Force password reset on first login"
                    model="settings.security.force_password_reset"
                />
                <x-settings.toggle-switch 
                    title="Session Timeout"
                    description="Automatically log out inactive users"
                    model="settings.security.session_timeout_enabled"
                />
            </div>
        </div>

        <div class="p-4 bg-amber-900/20 border border-amber-800 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-amber-400 mt-0.5"></i>
                <div>
                    <h5 class="text-sm font-medium text-amber-200">Security Notice</h5>
                    <p class="text-xs text-amber-300 mt-1">
                        Changes to security settings will take effect immediately and may require users to re-authenticate.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-settings.settings-card>
