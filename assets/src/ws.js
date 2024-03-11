export default class Ws {
    constructor(url) {
        this.url = url;
    }

    get newClientPromise() {
        return new Promise((resolve, reject) => {
            let wsClient = new WebSocket(this.url);
            wsClient.onopen = () => {
                resolve(wsClient);
            };
            wsClient.onerror = error => reject(error);
            wsClient.onmessage = message => message;
        })
    }

    get clientPromise() {
        if (!this.promise) {
            this.promise = this.newClientPromise
        }
        return this.promise;
    }
}