import { expand_content } from "./utils.js";

async function main() {

    expand_content(".expand-button", ".watchlist-content", ".hidden", "watchlist-item-metadata", "minimize-button");

}

main();