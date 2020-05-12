<p align="center">
  <a href="" rel="noopener">
 <img width=200px height=200px src="" alt="LRF logo"></a>
</p>

<h3 align="center">LRF (Laravel Rest Framework)</h3>

<div align="center">

[![Status](https://img.shields.io/badge/status-active-success.svg)]()
[![GitHub Issues](https://img.shields.io/github/issues/kylelobo/The-Documentation-Compendium.svg)](https://github.com/mkhuramj/laravel-rest-framework/issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/kylelobo/The-Documentation-Compendium.svg)](https://github.com/mkhuramj/laravel-rest-framework/pulls)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](/LICENSE)

</div>

---

<p align="center"> LRF allows you to integrate the Permissions, Filters, Filtered Querysets per action, Validation with Laravel (Lumen) as used in Django Rest Framework (DRF).
    <br> 
</p>

## 📝 Table of Contents

- [About](#about)
- [Authors](#authors)
- [Acknowledgments](#acknowledgement)

## 🧐 About <a name = "about"></a>

I have been developing using laravel for 4 years and got the chance to use Django and DRF. I like the way DRF allows to attach and handle Permissions, Filters, Validation and very easy (inital) CRUD with the help of mixins nicely used with views (controllers in Laravel). So when I came back to Laravel I had the options like Gates and Policy to authorize the users. But I wanted to have something like DRF provides for the Permissions and Filters and a nicely filtered querySet for each of your requests and it can further be filtered for role bases. So getting that idea, of using Permissions, Filters, Validation etc, from DRF, I decided to make something like that for Laravel (and later for Lumen).

This will help those who are coming from DRF to Laravel or those who want an easy way to implement permissions and filter for the incoming requests. Also, it will give a new dimention to the Laravel community. 

Another nice thing about this package is you only need to run the lrf:model --all and it will create Controller, Model, ApiRequest (FormRrequest), Resources, Permission and Filter. To start the crud for the newly create model, you only need to write the migration, add fillable fields in the fillable array of the model, add the resource route in the api route and you are ready to CRUD with Laravel :-)


## ✍️ Authors <a name = "authors"></a>

- [@mkhuramj](https://github.com/mkhuramj) - Idea & Initial work

See also the list of [contributors](https://github.com/mkhuramj/laravel-rest-framework/contributors) who participated in this project.

## 🎉 Acknowledgements <a name = "acknowledgement"></a>

- Laravel's extension
- Inspired from Django Rest Framework
