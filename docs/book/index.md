# laminas-mvc-plugin-file-prg

Laminas MVC plugin for providing Post-Redirect-Get workflows with uploaded files.

## Installation

Install via composer:

```bash
$ composer require laminas/laminas-mvc-plugin-fileprg
```

If you are using the [laminas-component-installer](https://docs.laminas.dev/laminas-component-installer/),
you're done!

If not, you will need to add the component as a module to your
application. Add the entry `'Laminas\Mvc\Plugin\FilePrg'` to
your list of modules in your application configuration (typically
one of `config/application.config.php` or `config/modules.config.php`).

## Usage

While similar to the [Post/Redirect/Get Plugin](https://docs.laminas.dev/laminas-mvc-plugin-prg/),
the File PRG Plugin will work for forms with file inputs.
The difference is in the behavior: The File PRG Plugin will interact
directly with your form instance and the file inputs, rather than
_only_ returning the POST params from the previous request.

By interacting directly with the form, the File PRG Plugin will turn off any
file inputs `required` flags for already uploaded files (for a partially valid
form state), as well as run the file input filters to move the uploaded files
into a new location (configured by the user).

> ### Files must be relocated on upload</h3>
>
> You __must__ attach a filter for moving the uploaded files to a new location, such as the
> [RenameUpload Filter](https://docs.laminas.dev/laminas-filter/file/#renameupload),
> or else your files will be removed upon the redirect.

This plugin is invoked with three arguments:

- `$form`: the `Laminas\Form\Form` instance.
- `$redirect`: (Optional) a string containing the redirect
  location, which can either be a named route or a URL, based on the
  contents of the third parameter. If this argument is not provided, it
  will default to the current matched route.
- `$redirectToUrl`: (Optional) a boolean that when set to
  `true`, causes the second parameter to be treated as a URL
  instead of a route name (this is required when redirecting to a URL
  instead of a route). This argument defaults to
  `false`.

### Example Usage

```php
$myForm = new Laminas\Form\Form('my-form');
$myForm->add([
    'type' => 'Laminas\Form\Element\File',
    'name' => 'file',
]);

// NOTE: Without a filter to move the file,
//       our files will disappear between the requests
$myForm->getInputFilter()->getFilterChain()->attach(
    new Laminas\Filter\File\RenameUpload([
        'target'    => './data/tmpuploads/file',
        'randomize' => true,
    ])
);

// Pass in the form and optional the route/url you want to redirect to after the POST
$prg = $this->fileprg($myForm, '/user/profile-pic', true);

if ($prg instanceof Laminas\Http\PhpEnvironment\Response) {
    // Returned a response to redirect us.
    return $prg;
}

if ($prg === false) {
    // First time the form was loaded.
    return ['form' => $myForm];
}

// Form was submitted.
// $prg is now an array containing the POST params from the previous request,
// but we don't have to apply it to the form since that has already been done.

// Process the form
if ($form->isValid()) {
    // ...Save the form...
    return $this->redirect()->toRoute('/user/profile-pic/success');
}

// Form not valid, but file uploads might be valid and uploaded
$fileErrors = $form->get('file')->getMessages();
if (empty($fileErrors)) {
    $tempFile = $form->get('file')->getValue();
}
```
