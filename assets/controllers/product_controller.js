import { Controller } from 'stimulus';
import axios from "axios";

export default class extends Controller {
    page = 0;
    pages = 0;
    limit = 0;
    total = 0;

    static values = {
        url: String
    };

    static targets = ['result'];

    connect() {
        this.index();
    }

    index() {
        axios.get(`${this.urlValue}?serializerGroups[]=article&serializerGroups[]=article.catalogs&serializerGroups[]=catalogs`).then((result) => {
            let toReturn = '';
            const data = result.data;

            this.page = data.page;
            this.pages = data.pages;
            this.limit = data.limit;
            this.total = data.total;

            data._embedded.items.forEach((article) => {
                let image = `
                    <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <title>Placeholder</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                    </svg>`;

                if (article._embedded && article._embedded.photo) {
                    image = `<img src="${article._embedded.photo}" class="bd-placeholder-img" width="200" height="250">`;
                }

                const categories = [];

                article.catalogs.forEach((catalog) => {
                    categories.push(catalog.name);
                })

                toReturn += `
                    <div class="col-md-6">
                        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                            <div class="col p-4 d-flex flex-column position-static">
                                <strong class="d-inline-block mb-2 text-primary">${categories.join(' ')}</strong>
                                <h3 class="mb-0 custom-ellipsis">${article.name}</h3>
                                <div class="mb-1 text-muted">${article.price}</div>
                                    <p class="card-text mb-auto custom-ellipsis">${article.description}</p>
                                </div>
                                <div class="col-auto d-none d-lg-block">
                                    ${image}                                    
                                </div>
                        </div>
                    </div>`;
            });

            this.resultTarget.innerHTML = toReturn;
        });
    }
}