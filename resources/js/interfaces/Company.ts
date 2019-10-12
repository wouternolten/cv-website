import { Url } from "url";

export interface Company {
    id: number;
    name: string;
    city: string;
    url: Url;
    created_at: Date;
    updated_at: Date;
}
