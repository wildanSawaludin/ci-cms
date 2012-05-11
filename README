/*

-- Introduction --
This project is a migration of the ci-cms content management system
from Code Igniter 1.7.x and Matchbox to Code Igniter 2.x and Modular
-Extensions. 


-- Reference --
The Module-Extensions system can be found on Bitbucket.Org at:
https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/overview

Further Information on MX can be found on Phil Sturgeon's website at:


The Code Igniter PHP Framework can be found at:
https://bitbucket.org/ellislab/codeigniter/overview

A bleeding edge Code Igniter repository with public fixes can be found at:
https://bitbucket.org/ellislab/codeigniter-reactor/overview


-- Reasoning -- 
The reason for moving to Modular-Extensions and away from Matchbox is
due to the lack of compatibility and lack of continued support by
Matchbox for Code Igniter versions beyond 1.5. While there were patches
on the web to make Matchbox work with Code Igniter versions up to 1.7.x 
these were not supported and in fact have vanished into vapor-ware.


-- Migration Approach --
This project will start with a fresh Code Igniter 2.0 install and add
the Modular-Extensions system. Next, we will migrate the admin module
and the underlying libraries i.e. System, Administration, Navigation,
etc. Then we shall takle the page module. Other module will follow
until we have migrated all modules to CI 2.x.


-- Considerations --
-- Routes
The Modular-Extensions is installed as a set of third party libraries
that extend the core CI libraries. The change to Modular-Extensions does 
raise two initial issues. First, the router will need modification to
handle module admin routes i.e. admin/page. As the current MX_Router
class will attempt to find a page controller in the admin module. This 
is actually a very simple fix thanks to the clean code in the MX_Router.
Simply reversing the first two uri segments and testing for this new 
route first solves the issue. However, this does mean that the MX_Router
cannot be used out of the box. 

-- Layout
The Modular-Extension system will always look in the module's views
directory first. Then if the view is not found there, it will look
in the application/views folder. It does nothing to search subdirectories
in the application/views folder. These means that our themes will not be 
found with the current Layout library. The two solutions to this issue 
are to modify the Layout library, or change all view files or both.

My decission was to include a new system settings called theme_dir. This
allows us to set a theme directory that contains all themes. Then minor 
modifications to the Layout library made these themse avlaible. A search 
and replace in all view files for the string "$this->system->theme" and 
replace it with "$this->system->theme_dir.$this->system->theme" will
allow us to use the current System library with little of few changes
and give us a convient themes directory to hold all themes.

-- Navigation
The current Navigation library makes calls to $this->db->orderby().
This method has been changed to $this->db->order_by(). There are three 
instances where this call is made.


 





