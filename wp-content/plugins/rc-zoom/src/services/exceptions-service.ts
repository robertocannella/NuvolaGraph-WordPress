import create from "./http-service";

export interface ExceptionService {
    id: number;
    name: string;
}

export default create('/wp-json/rc-book-zoom/v1/exceptions');