import axios, {CanceledError, CreateAxiosDefaults} from 'axios';

export default axios.create<CreateAxiosDefaults|undefined>({
    baseURL: globalSiteData.siteUrl as string,
    headers: {
        'content-type': 'application/json',
        'X-WP-Nonce': globalSiteData!.nonceX as string
    }
});

export { CanceledError };