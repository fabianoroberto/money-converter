import { Controller } from 'stimulus';

export default class extends Controller {
    static values = {
        url: String
    };

    static targets = ['result'];

    connect() {
        console.log('connected');
        this.index();
    }

    async index() {
        const articles = await fetch(`${this.urlValue}`);
        //this.resultTarget.innerHTML = await articles.text();
    }

    async search(query) {
        const params = new URLSearchParams({
            q      : query,
            preview: 1
        });
        const response              = await fetch(`${this.urlValue}?${params.toString()}`);
        this.resultTarget.innerHTML = await response.text();
    }
}