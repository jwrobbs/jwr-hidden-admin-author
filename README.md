# Hidden Admin Author

Hidden Admin Author does 1 thing: hide admin users. Specifically, it changes the author for any post authored by an administrator.

The intent is to create a honeypot target for bots. If you use an unused account as the author, any login attempt can be treated as hostile.

It also disable the Users portion of the REST API. I will add a toggle for this in the options. I may adjust it to only hide administrators without disabling the endpoint.

**\*Note** This system requires ACF Pro and uses [JWR's Control Panel](https://github.com/jwrobbs/jwr-control-panel) as a submodule.\*

Status: **_Beta_**

## Usage

Once configured, posts will be updated whenever they are saved.

Configuration:

-   Ensure ACP Pro is installed and activated.
-   Install and activate Hidden Admin Author.
-   Create your dummy author. Ensure it has a proper display name. Remember the user ID.
-   Open the control panel (default name: JWR Control Panel).
-   Go to the _Hiden Admin Author_ tab.
-   Enter the user ID and click save.

## Installation

## Contact

Have a question or comment, hit me up at https://twitter.com/_JoshRobbs
