//import './bootstrap.js';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import Inputmask from "inputmask";

document.addEventListener("DOMContentLoaded", () => {
    Inputmask({regex: "CRMV-[A-Z]{2}-[0-9]{4,6}"}).mask(document.querySelectorAll(".input-crmv"));
    Inputmask({regex: "[A-Z]{3}-[0-9]{4}-[0-9]{4,6}"}).mask(document.querySelectorAll(".input-code"));
});

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
