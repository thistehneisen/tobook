#jslint browser: true, nomen: true, unparam: true

#global $, jQuery, unescape
"use strict"

# modify String prototype to have more helpers for JS string
unless String::format
  String::format = ->
    args = arguments
    @replace /\{(\d+)\}/g, (match, number) ->
      (if args[number] isnt `undefined` then args[number] else match)

unless String::routeformat
  String::routeformat = ->
    args = arguments
    @replace /\{(\w+)\}/g, (match, key) ->
      (if args[0][key] isnt `undefined` then args[0][key] else match)

# routes
VARAA.routes = {}
VARAA.addRoute = (routeName, url) ->
  VARAA.routes[routeName] = unescape(url)
  return

VARAA.getRoute = (routeName, params) ->
  route = VARAA.routes[routeName]
  if route isnt `undefined`
    return route  if params is `undefined`
    return route.routeformat(params)
  console.log "Error getting route \"{0}\"".format(routeName)
  return


# langs
$body = $ 'body'
VARAA.currentLocale = if $body.data 'locale' isnt `undefined` then $body.data 'locale' else 'en'
VARAA._i18n = {}
$.getJSON $body.data('js-locale'), (data) ->
  VARAA._i18n = data
  return

# I hate global var but this is faster to type and also consistent with Laravel
trans = (key) ->
  return VARAA._i18n[key]  if VARAA._i18n.hasOwnProperty(key)
  key
