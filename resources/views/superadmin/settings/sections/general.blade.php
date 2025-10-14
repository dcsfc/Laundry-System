<x-settings.settings-card 
    title="General Settings" 
    description="Basic shop information and system preferences"
    icon="fas fa-store"
>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-settings.form-input 
                type="text"
                label="Shop Name"
                model="settings.general.shop_name"
                placeholder="Latino Laundry Shop"
                required="true"
            />
            <x-settings.form-input 
                type="text"
                label="Owner Name"
                model="settings.general.owner_name"
                placeholder="Juan Dela Cruz"
            />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-settings.form-input 
                type="text"
                label="Phone Number"
                model="settings.general.phone"
                placeholder="+63 912 345 6789"
            />
            <x-settings.form-input 
                type="email"
                label="Contact Email"
                model="settings.general.email"
                placeholder="contact@latino-laundry.ph"
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-settings.form-input 
                type="select"
                label="Timezone"
                model="settings.general.timezone"
                :options="[
                    'Asia/Manila' => 'Philippine Standard Time (PST)',
                    'Asia/Shanghai' => 'China Standard Time',
                    'Asia/Tokyo' => 'Japan Standard Time',
                    'Asia/Seoul' => 'Korea Standard Time'
                ]"
            />
            <x-settings.form-input 
                type="select"
                label="Default Language"
                model="settings.general.language"
                :options="[
                    'en' => 'English',
                    'fil' => 'Filipino',
                    'tl' => 'Tagalog'
                ]"
            />
        </div>

        <x-settings.form-input 
            type="textarea"
            label="Shop Address"
            model="settings.general.address"
            placeholder="123 Rizal Street, Barangay Poblacion, Manila 1000"
        />

        <x-settings.toggle-switch 
            title="Maintenance Mode"
            description="Temporarily disable the system for maintenance"
            model="settings.general.maintenance_mode"
        />
    </div>
</x-settings.settings-card>
