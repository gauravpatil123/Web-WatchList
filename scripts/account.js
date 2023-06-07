import { expand_content } from "./utils.js";

async function main() {

    expand_content(".expand-button-watched", ".watched-list-content", ".hidden", "watched-list-item-metadata", "minimize-button-watched");
    expand_content(".expand-button-removed", ".removed-list-content", ".hidden", "removed-list-item-metadata", "minimize-button-removed");

}

main();