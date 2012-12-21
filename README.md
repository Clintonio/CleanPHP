CleanPHP
========

A PHP Library that makes PHP simpler and more object oriented with many useful modules

Installation
========

1. Copy CleanPHP directory into your project, such as <PROJECT_DIR>/lib/CleanPHP
2. Add a require_once("<PROJECT_DIR>/lib/CleanPHP/CleanPHP.php"); into the code that will use CleanPHP
3. Add your own module path with CleanPHP::addModulePath("<PATH_TO_MODULES>"); after requiring CleanPHP
4. Import modules with CleanPHP::import("<the.module.path>");, where a "." designates a submodule

