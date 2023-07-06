import axios from "axios";

export class MyAvailability {
    constructor() {
        this.api = axios.create({
            baseURL: globalSiteData.siteUrl,
            headers: {
                'content-type': 'application/json',
                'X-WP-Nonce': globalSiteData.nonceX
            }
        });
        this.getAvailability(1).then((response=>console.log(response))).catch(e=>console.log(e));
    }
    async getAvailability(id){
        return  await this.api.get(`/wp-json/rc-book-zoom/v1/availability?user_id=${id}`)
    }
    async getExceptions(id){
        return  await this.api.get(`/wp-json/rc-book-zoom/v1/exceptions?user_id=${id}`)
    }

}