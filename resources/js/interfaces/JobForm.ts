import { Company } from "./company";

export interface JobForm {
    all_tags: string[];
    companies: Company[];
    company_form: FormField[];
    job_form: FormField[];
    tags: string[];
}

export interface FormField {
    labelName: string;
    name: string;
    required?: boolean;
    options?: string[];
    type: string;
    value?: any;
}
