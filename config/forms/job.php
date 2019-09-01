<?php

return [
    ['name' => 'job_type', 'labelName' => 'Job type', 'type' => 'select', 'options' => 'job_types', 'required' => 'true'],
    ['name' => 'function_name', 'labelName' => 'Function name', 'type' => 'text', 'required' => 'true'],
    ['name' => 'start_date', 'labelName' => 'Start date', 'type' => 'date', 'required' => 'true'],
    ['name' => 'end_date', 'labelName' => 'End date (leave empty if this is still going)', 'type' => 'date'],
    ['name' => 'responsibilities', 'labelName' => 'Responsibilities', 'type' => 'text']
];
