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
VARAA.routes = VARAA.routes || {}
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
VARAA.trans = (key) ->
  return VARAA._i18n[key]  if VARAA._i18n.hasOwnProperty(key)
  key

# regex email validation pattern
# http://stackoverflow.com/questions/2507030/email-validation-using-jquery
VARAA.regex_email_validation = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/

VARAA.getLocation = ->
  q = $.Deferred()
  success = (position) ->
    lat = position.coords.latitude
    lng = position.coords.longitude

    # Update location values in Session, so that users won't be asked again
    $.ajax
      url: VARAA.routes.updateLocation
      type: 'POST'
      data:
        lat: lat
        lng: lng
    .done ->
      q.resolve lat, lng

  error = (err) -> q.reject err
  navigator.geolocation.getCurrentPosition success, error, timeout: 10000

  return q.promise()

VARAA.initTypeahead = (selector, name) ->
  collection = new Bloodhound
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace 'name'
    queryTokenizer: Bloodhound.tokenizers.whitespace
    limit: 10
    prefetch:
      url: selector.data('data-source')

  collection.clearPrefetchCache()
  collection.initialize()

  selector.data 'bloodhound', collection

  selector.typeahead
    highlight: true
    hint: true
  ,
    name: name
    displayKey: 'name'
    source: collection.ttAdapter()
    templates:
      suggestion: (item) ->
        "<div data-url=#{item.url}>#{item.name}</div>"

