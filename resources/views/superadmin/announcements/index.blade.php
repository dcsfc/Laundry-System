@extends('layouts.sidebar')

@section('title', 'Announcements Management - Super Admin')

@section('content')
    <div class="container">
        <!-- Announcements Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :data="$announcements"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :current-page="1"
            :empty-message="'No announcements found'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-gray-800 text-gray-200'"
            :title="'Announcements Management'"
            :add-button="true"
            :add-button-label="'Create Announcement'"
            :add-button-action="'createAnnouncement'"
        />
    </div>

    <script>
        // Announcements Management Functions for Data Table Component
        function viewAnnouncement(row) {
            console.log('View announcement:', row);
            alert('View announcement: ' + row.id + ' - ' + row.title);
        }

        function editAnnouncement(row) {
            console.log('Edit announcement:', row);
            alert('Edit announcement: ' + row.id + ' - ' + row.title);
        }

        function toggleAnnouncementStatus(row) {
            console.log('Toggle status for announcement:', row);
            const newStatus = row.status === 'Active' ? 'Inactive' : 'Active';
            if (confirm(`Are you sure you want to ${newStatus.toLowerCase()} announcement ${row.id}?`)) {
                alert(`Announcement ${row.id} status updated to ${newStatus}`);
            }
        }

        function deleteAnnouncement(row) {
            console.log('Delete announcement:', row);
            if (confirm('Are you sure you want to delete announcement ' + row.id + '? This action cannot be undone.')) {
                alert('Announcement ' + row.id + ' deleted');
            }
        }

        function createAnnouncement() {
            console.log('Create new announcement');
            alert('Create new announcement form would open here');
        }
    </script>
@endsection