# Gloves Framework#

**Gloves** is a simple framework created to make Wordpress plugin development faster and less annoying.

##Start ##

Just download `.zip` file and unzip it the plugins directory of Wordpress installation.

###Configuration###

Open `conf.php` and complete the array with plugin name and text-domain. In this file you can also change project directories.

### Setup ###

Open `cmd` in your plugin's directory. 

To create your main plugin file and structure use:
`php glovesCLI.php setup`

After this your plugin is basically created and now it's your job to make something nice :)

## Making a module ##

Every component that you will add to your plugin is a module. You can add it by using command:

```php glovesCLI.php make_module <name> <template>```

Template parameter has default default value 'standard'. Those templates are in Gloves\Template directory. You can add your own too.

Your new module will be in Module folder. When model is created you have to add it's name to the `$modules` array in main plugin file.

```
   protected static $modules = [
	   yourModule => parametersOnInit
   ];
```
##Model ##

Creating your own database table is very simple.

```
php glovesCLI.php make_model <name>
```

It will create file in Model folder. After that you have to create fields for table this way:

```
protected static $fields = [
	'field_name' => 'type'
];
```

ID field is added automatically. Type has to be MySql type. Then you have to add version of this table.

`protected static $version = '1.0';`

Plugin checks if the table version changed and then recreates the table. All data will be lost on update so be careful with this.

## Flow ##

Every module can have 4 functions to determine behavior on actions such as first activation, activation, deactivation, uninstall. Besides it has initialization function which is called on every plugin usage with arguments given in modules array.

## Documentation ##

Extended documentation will be available soon.