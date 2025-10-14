<x-settings.settings-card 
    title="Notifications" 
    description="Configure email and in-app notification preferences"
    icon="fas fa-bell"
>
    <div class="space-y-6">
        <div class="space-y-4">
            <h4 class="text-sm font-medium text-white">Email Notifications</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-settings.form-input 
                    type="text"
                    label="SMTP Host"
                    model="settings.notifications.smtp_host"
                    placeholder="smtp.gmail.com"
                />
                <x-settings.form-input 
                    type="number"
                    label="SMTP Port"
                    model="settings.notifications.smtp_port"
                    placeholder="587"
                />
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-settings.form-input 
                    type="text"
                    label="SMTP Username"
                    model="settings.notifications.smtp_username"
                    placeholder="your-email@latino-laundry.ph"
                />
                <x-settings.form-input 
                    type="password"
                    label="SMTP Password"
                    model="settings.notifications.smtp_password"
                    placeholder="••••••••"
                />
            </div>
        </div>

        <div class="space-y-4">
            <h4 class="text-sm font-medium text-white">Notification Preferences</h4>
            <div class="space-y-3">
                <x-settings.toggle-switch 
                    title="Email Notifications"
                    description="Send email notifications for important events"
                    model="settings.notifications.email_enabled"
                />
                <x-settings.toggle-switch 
                    title="In-App Notifications"
                    description="Show notifications within the application"
                    model="settings.notifications.in_app_enabled"
                />
                <x-settings.toggle-switch 
                    title="Order Status Updates"
                    description="Notify customers when order status changes"
                    model="settings.notifications.order_updates"
                />
                <x-settings.toggle-switch 
                    title="Payment Confirmations"
                    description="Send payment confirmation emails"
                    model="settings.notifications.payment_confirmations"
                />
            </div>
        </div>
    </div>
</x-settings.settings-card>
