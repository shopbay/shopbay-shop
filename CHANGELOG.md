# Change Log (shopbay-shop)

## Version 0.25 - Aug 5, 2018

This release contains several enhancements and bug fixes, as well as also supports PHP 7.2 and also Yii 1.1.20 and Yii 2.0.15
 
### Enhancements:

 - Enh: Upgraded to support PHP 7.2
 - Enh: Upgraded to support Yii 1.1.20 and Yii 2.0.15
 - Enh: Change google analytics tracking to use gtag.js
 - Enh: Add two new buttons: 'View Demo Shop' and 'View Demo Chatbot' at index page
 - Enh: Enhance website to have web content management capability
 - Read OPEN_SOURCE_URL via Config::getSystemSetting('repo_source_link'). 
 - Chg:  Change the loading sequence of Sii messages of each module. It auto merges message in sequence:
(1) application level messages
(2) common module level messages (inclusive of kernel common messages)
(3) local module level messages

### Bug fixes:

 - Bug: Item price update via class ItemPriceGetAction need to set shop theme before calling controller->getThemeView()


## Version 0.24 - Jun 24, 2017

This is the initial release of `shopbay-shop`, part of Shopbay.org open source project. 

It includes code re-architecture and refactoring to separate the `shop` module and `customer` app out from old code.
All existing functions and features remain same as inherited from previous code base (v0.23.2).

For full copyright and license information, please view the [LICENSE](LICENSE.md) file that was distributed with this source code.


## Version 0.23 and before - June 2013 to March 2017

Started since June 2013 as private development, the beta version (v0.1) was released at September 2015. 

Shopbay.org open source project was created by forking from beta release v0.23.2 (f4f4b25). 