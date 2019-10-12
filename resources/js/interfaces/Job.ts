export interface Job {
    id: number;
    user_id: number;
    company_id: number;
    function_name: string;
    start_date: Date;
    end_date?: Date;
    job_type: string;
    responsibilities: string;
    created_at: Date;
    updated_at: Date;
}
