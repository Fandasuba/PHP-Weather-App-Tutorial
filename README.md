This is a demo I am bulding to learn some PHP on the side. The aim of the project is to build a custom weather app, with the ability to pull data from the Met Office and other sources and cache it, aggregate it and then render it on a front end website. This will teach me a fair bit about Server Side Rendering and SEO, alongside aims to create prorper seo friendly hytperlinks, such as /weather/london, rather than an faceted link like weather?city=london type links.


## Aims and objectives
- Cache weather reports for 30 minute periods to reduce API rate requests and increase better page response times for front end, as it can check data much easier.
- Build JS to interact with weather charts, auto refreshes when possible.
- Implement routing for SEO firnedly keywords like weather location keywords.
- add features like detect my location in the browser to pass location data, rather than typing in location manually everytime. Optional request ot toggle it similar to other browsers that ask for location data.
- JSON-LD for SEO structured data for rich snippets. 
- Dynamic meta tags for current weather conditions.
