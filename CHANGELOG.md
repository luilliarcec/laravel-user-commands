# Changelog

All notable changes to `laravel-user-commands` will be documented in this file

#3.1.0 - 2021-09-02
- Add feature to attach all permissions.

## 3.0.0 - 2021-07-20
- Fixed the bug that did not allow saving the user when the fillable property was used.
- Removed default fields and default rules.
- Added two keys to config file `fields` and` rules`.
- Added rule `filled` dynamically to all your fields defined in your fillable property and/or in your config file.
- Add Dynamic question for fields that need to be confirmed.

## 2.2.0 - 2021-06-27
- Add support for asking fillable fields 

## 2.1.0 - 2021-04-20
- Exposing methods to overwrite user data
    - Add `merge` function
    - Add `prepareForSave` function
    - Add Tests

## 2.0.0 - 2021-04-19
- Using flag field (Option) instead of argument
- Exposing user default data

## 1.0.1 - 2021-04-18
- Inputs ask refactoring

## 1.0.0 - 2020-12-29
- Initial release
