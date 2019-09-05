import { IFormService } from "../../services/IFormService";

export interface FormTypes {
    fields: [],
    errors: {},
    success: boolean,
    loaded: boolean,
    action: string,
    formService: IFormService
}
