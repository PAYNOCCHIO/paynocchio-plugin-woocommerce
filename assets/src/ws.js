export default class Ws {
    constructor(url) {
        this.url = url;
    }

    get newClientPromise() {
        return new Promise((resolve, reject) => {
            let wsClient = new WebSocket(this.url);
            console.log(wsClient)
            wsClient.onopen = () => {
                console.log("connected");
                resolve(wsClient);
            };
            wsClient.onerror = error => reject(error);
        })
    }

    get clientPromise() {
        if (!this.promise) {
            this.promise = this.newClientPromise
        }
        return this.promise;
    }
}