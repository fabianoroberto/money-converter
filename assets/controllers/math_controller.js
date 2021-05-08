import { Controller } from 'stimulus';
import $ from 'jquery';
import axios from "axios";

export default class extends Controller {
    static values = {
        sum: String,
        sub: String,
        mul: String,
        div: String,
    };

    static targets = ['result'];

    onSubmit(event) {
        const operand1 = $('#operand1').val();
        const operand2 = $('#operand2').val();
        const operator = $('#operator').val();

        $(this.resultTarget).html('')
        $('.spinner-border').show();

        let xhr;

        switch (operator) {
            case 'sum':
                xhr = axios.post(this.sumValue, {
                    addend1: operand1,
                    addend2: operand2,
                });

                break;
            case 'sub':
                xhr = axios.post(this.subValue, {
                    minuend: operand1,
                    subtrahend: operand2,
                });

                break;
            case 'mul':
                xhr = axios.post(this.mulValue, {
                    factor1: operand1,
                    factor2: operand2,
                });

                break;
            case 'div':
                xhr = axios.post(this.divValue, {
                    dividend: operand1,
                    divisor: operand2,
                });

                break;
        }

        event.preventDefault();

        xhr.then((response) => {
            $('.spinner-border').hide();
            $(this.resultTarget).html(response.data.result)
        }).catch(() => {
            $('.spinner-border').show();
        });
    }
}