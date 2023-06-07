function expand_content(expand_button_query, parent_class, hidden_child, toggle_data_class, minimize_button_query) {

    $(expand_button_query).on("click", function() {

        $(this).parents(parent_class).children(hidden_child).toggleClass(toggle_data_class);
        $(this).toggleClass(minimize_button_query);

    })

}

export { expand_content };