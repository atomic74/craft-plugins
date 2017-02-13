# Web Form plugin for Craft CMS

This plugin allows to configure individual submission forms through **Web Form** entry type.

[Release Notes](https://github.com/ohlincik/craft-plugins/tree/master/web-form/docs/release-notes)

## Form Tag

The following Twig code will generate the form tag:

    {{ craft.webForm.formTag({
      'entryId': entry.id,
      'captchaLanguage': 'en',
      'captchaTimeout': '120',
      'captchaMessage': 'Please complete the captcha above before submitting this form'
    }) }}

### Options

- **entryID:** Based on the ID the plugin will retrieve the rest of the needed settings directly from the **Form Settings** tab for the entry. **REQUIRED**
- **captchaLanguage:** Specify the language that should be used by the reCAPTCHA widget. Defaults to value of `en`.
- **captchaTimeout:** Specify the number of seconds the captcha validation should be valid. If the user does not submit the form within this time period, they will be asked to complete the captcha again. Defaults to value of `120`.
- **captchaMessage:** Specify the error message that will be displayed if the captcha validation is not complete or invalid. Defaults to value of `Please complete the captcha above before submitting this form`.

## Form Fields Available by Default

These fields need to be setup within the `Form Field` matrix.

### Section (formSection)

- Name (inputName, Plain Text)
- Title (sectionTitle, Plain Text)
- Show Divider (showDivider, Lightswitch, default: off)
- Description (description, Rich Text, Simple)

### Textbox (textbox)

- Name (inputName, Plain Text)
- Label (label, Plain Text)
- Placeholder (placeholder, Plain Text)
- Required (required, Lightswitch, default: off)
- Help Block (helpBlock, Plain Text)

### Text Area (textArea)

- Name (inputName, Plain Text)
- Label (label, Plain Text)
- Rows (rows, Number, Min: 2, Max: 20)
- Placeholder (placeholder, Plain Text)
- Required (required, Lightswitch, default: off)
- Help Block (helpBlock, Plain Text)

### Checkboxes (checkboxes)

- Name (inputName, Plain Text)
- Label (label, Plain Text)
- Options (options, Table, Columns: Label, Value)
- Required (required, Lightswitch, default: off)
- Help Block (helpBlock, Plain Text)

### Radio Buttons (radioButtons)

- Name (inputName, Plain Text)
- Label (label, Plain Text)
- Options (options, Table, Columns: Label, Value)
- Required (required, Lightswitch, default: off)
- Help Block (helpBlock, Plain Text)

### Dropdown (dropdown)

- Name (inputName, Plain Text)
- Label (label, Plain Text)
- Options (options, Table, Columns: Label, Value)
- Required (required, Lightswitch, default: off)
- Prompt Option (promptOption, Plain Text)
- Help Block (helpBlock, Plain Text)

### US States Dropdown (dropdownUsStates)

- Name (inputName, Plain Text)
- Label (label, Plain Text)
- Required (required, Lightswitch, default: off)
- Prompt Option (promptOption, Plain Text)
- Help Block (helpBlock, Plain Text)
