Handlebars.registerHelper('fbUserData', function($id, $field)
{
    return (cachedFbUserData[$id] && cachedFbUserData[$id][$field]) ? cachedFbUserData[$id][$field] : 'no_data';
});

Handlebars.registerHelper('ifIsMy', function($id, options)
{
    return ($id == auth_user_id) ? options.fn(this) : options.inverse(this);
});