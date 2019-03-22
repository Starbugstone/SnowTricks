/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

//init select elements
document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('select');
    if (elems.length === 0) {
        return;
    }
    var instances = M.FormSelect.init(elems);
});

//init materialBox
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.materialboxed');
    if (elems.length === 0) {
        return;
    }

    var instances = M.Materialbox.init(elems);
});

//init chips
document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.chips');
    if (elems.length === 0) {
        return;
    }

    let tagsData = document.querySelectorAll('.trick-tag-data')[0];
    //sanity checks and construct helpers
    if (!tagsData) {
        throw "missing the .trick-tag-data element that should contain all the chips data"
    }

    if (!("trickTagsJson" in tagsData.dataset) || !("allTagsJson" in tagsData.dataset)) {
        throw "missing data-trick-tags-json or data-all-tags-json in the .trick-tag-data element. These elements should contain all the json for set tags and all existing tags"
    }


    let trickTags = JSON.parse(tagsData.dataset.trickTagsJson);
    let allTags = JSON.parse(tagsData.dataset.allTagsJson);

    //creating the autocompleteOptions data object, needs to be of type { tag: null, tag2: null}
    let allTagsObj = {};
    allTags.map(
        t => {
            allTagsObj[t.name] = null;
            return allTagsObj;
        }
    );

    let trickTagsObj = trickTags.map(function (tt) {
        return {tag: tt.name} //mapping the name from json to tag: name
    });

    var instances = M.Chips.init(elems, {
        placeholder: "Add some tags",
        secondaryPlaceholder: "keep adding Tags",
        onChipAdd: chipInput,
        onChipDelete: chipInput,
        data: trickTagsObj,
        autocompleteOptions: {
            data: allTagsObj,
            limit: Infinity,
            minLength: 2
        }
    });

    function chipInput() {
        var elem = document.getElementById('chips1');

        var tagInput = document.getElementById('trick_type_form_tags');
        var instance = M.Chips.getInstance(elem);
        var tagData = instance.chipsData;
        var tags = [];
        for (let $i = 0; $i < tagData.length; $i++) {
            tags.push(tagData[$i].tag.toLowerCase());
        }
        let uniqTags = [...new Set(tags)];
        tagInput.value = JSON.stringify(uniqTags);
    }

});