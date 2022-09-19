/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

require('select2');
import 'select2/dist/css/select2.min.css';

$(document).ready(function() {
    $('.select2').select2();

    // hack to fix jquery 3.6 focus security patch that bugs auto search in select-2
    $(document).on('select2:open', () => {
        document.querySelector('.select2-container--open .select2-search__field').focus();
    });

    const addFormToCollection = (e) => {
        const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

        const item = document.createElement('div');
        item.classList.add("subformItem", "row", "align-items-center")

        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );

        collectionHolder.appendChild(item);

        collectionHolder.dataset.index++;

        document
            .querySelectorAll('.subformDeleteButton')
            .forEach((item) => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    item.closest('.subformItem').remove();
                });
            })

        $('.select2').select2();
    };

    document
        .querySelectorAll('.subformDeleteButton')
        .forEach((item) => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                item.closest('.subformItem').remove();
            });
        })

    document
        .querySelectorAll('.add_item_link')
        .forEach(btn => {
            btn.addEventListener("click", addFormToCollection)
        });
});


