<div>
    <x-table.default
        :cols="$this->cols"
        :rows="$this->users"
        :sortDirection="$sort_direction"
        :sortField="$sort_by"
    />
</div>
