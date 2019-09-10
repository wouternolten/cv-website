import axios from "axios";
import { IFormService } from "./IFormService";

export default class JobService implements IFormService {
    submit(url: string, data: any) {
        return axios.post(url, data);
    }
}
