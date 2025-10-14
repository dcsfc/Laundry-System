<!-- Loading Row -->
<template x-for="i in pageSize" :key="i">
    <tr class="loading-row">
        @foreach($columns as $column)
        <td class="data-cell">
            <div class="skeleton skeleton-text"></div>
        </td>
        @endforeach
        
        @if($actions)
        <td class="actions-cell">
            <div class="skeleton skeleton-actions"></div>
        </td>
        @endif
    </tr>
</template>
