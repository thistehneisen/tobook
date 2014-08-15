jQuery123.event.special.tourmyapp_typing = {
    setup: function (C, B) {
        var A = this;
        jQuery123(A).bind("keyup", jQuery123.event.special.tourmyapp_typing.handler)
    },
    teardown: function (B) {
        var A = this;
        jQuery123(A).unbind("keyup", jQuery123.event.special.tourmyapp_typing.handler)
    },
    handler: function (B) {
        var C = this;
        var A = jQuery123(C).data("typingTimer") || null;
        clearTimeout(A);
        if (B.keyCode == 13) {
            jQuery123.event.special.tourmyapp_typing.doneTyping(C)
        } else {
            if (jQuery123(C).val) {
                A = setTimeout(function () {
                    jQuery123.event.special.tourmyapp_typing.doneTyping(C)
                }, 1000);
                jQuery123(C).data("typingTimer", A)
            }
        }
    },
    doneTyping: function (A) {
        jQuery123(A).trigger("tourmyapp_typing")
    }
};

function TourMyAppSessionStorage() {}
TourMyAppSessionStorage.prototype.setItem = function (C, D, E) {
    var A;
    if (E) {
        var B = new Date();
        B.setTime(B.getTime() + (E * 24 * 60 * 60 * 1000));
        A = "; expires=" + B.toGMTString()
    } else {
        A = ""
    }
    document.cookie = C + "=" + D + A + "; path=/"
};
TourMyAppSessionStorage.prototype.getItem = function (B) {
    var D = B + "=";
    var A = document.cookie.split(";");
    for (var C = 0; C < A.length; C++) {
        var E = A[C];
        while (E.charAt(0) == " ") {
            E = E.substring(1, E.length)
        }
        if (E.indexOf(D) == 0) {
            return E.substring(D.length, E.length)
        }
    }
    return null
};
TourMyAppSessionStorage.prototype.removeItem = function (A) {
    this.setItem(A, "", -1)
};

function TourMyAppRepository(A) {
    this.client = A
}
TourMyAppRepository.prototype.getBaseUrl = function () {
    return this.client._getOption("url")
};
TourMyAppRepository.prototype.sendLogData = function (A, F, E, D, C) {
    var B = this;
    jQuery123.ajax({
        type: "get",
        url: B.getBaseUrl() + "/scenarios/logs/add/",
        dataType: "jsonp",
        data: {
            action: E,
            scenario_id: A,
            user_reference: F,
            step: D
        },
        success: function (G) {
            C(G)
        }
    })
};
TourMyAppRepository.prototype.fetchTourDetails = function (F, E, C, B) {
    var A = this;
    var D = {
        user_reference: E
    };
    if (C) {
        D.force = true
    }
    jQuery123.ajax({
        url: A.getBaseUrl() + "/scenarios/" + A.client.username + "/scenario/" + F + "/",
        type: "GET",
        dataType: "jsonp",
        data: D,
        success: function (G) {
            B(G)
        }
    })
};

function TourMyAppStepConditionChecker() {}
TourMyAppStepConditionChecker.prototype.createPattern = function (A) {
    return A.replace("*", "[^/]+")
};
TourMyAppStepConditionChecker.prototype.getCurrentUrl = function () {
    return window.location.href
};
TourMyAppStepConditionChecker.prototype.checkUrlCondition = function (F) {
    var C = F.step_meta["condition"]["condition"];
    var G = F.step_meta["condition"]["url_patterns"];
    var E = this.getCurrentUrl();
    var A = null;
    for (var D = 0; D < G.length; D++) {
        var B = new RegExp(this.createPattern(G[D]), "gmi");
        A = E.match(B);
        if (A) {
            break
        }
    }
    if (C == "match") {
        return !!A
    } else {
        return !!!A
    }
};
TourMyAppStepConditionChecker.prototype.isElementPresent = function (A) {
    return jQuery123(A).length > 0
};
TourMyAppStepConditionChecker.prototype.checkDomCondition = function (D) {
    var B = D.step_meta["condition"]["condition"];
    var E = D.step_meta["condition"]["dom_patterns"];
    var A = null;
    for (var C = 0; C < E.length; C++) {
        A = this.isElementPresent(E[C]);
        if (A) {
            break
        }
    }
    if (B == "match") {
        return A
    } else {
        return !A
    }
};
TourMyAppStepConditionChecker.prototype.checkForStepMeta = function (A) {
    if (!A.step_meta) {
        return false
    }
    if (!A.step_meta["condition"]) {
        return false
    }
    return true
};
TourMyAppStepConditionChecker.prototype.checkConditions = function (A) {
    if (A.step_meta["condition"]["type"] == "url") {
        return this.checkUrlCondition(A)
    }
    if (A.step_meta["condition"]["type"] == "dom") {
        return this.checkDomCondition(A)
    }
};
TourMyAppStepConditionChecker.prototype.shouldSkipStep = function (A) {
    if (!this.checkForStepMeta(A)) {
        return false
    }
    if (A.step_meta["condition"]["on_condition"] != "skip") {
        return false
    }
    return this.checkConditions(A)
};
TourMyAppStepConditionChecker.prototype.shouldShowMessage = function (A) {
    if (!this.checkForStepMeta(A)) {
        return false
    }
    if (A.step_meta["condition"]["on_condition"] != "show_message") {
        return false
    }
    return this.checkConditions(A)
};

function TourMyApp(B, A) {
    this.debug("TourMyApp::");
    if (typeof TourMyApp.single_instance === "undefined") {
        this.debug("TourMyApp::new instance created");
        TourMyApp.single_instance = this;
        this.running = false;
        this._options = {
            url: "https://tour.tourmyapp.com",
            storage: new TourMyAppSessionStorage(),
            step_checker: new TourMyAppStepConditionChecker(),
            repository: new TourMyAppRepository(this),
            display_branding: true,
            translations: {
                "default": {
                    Next: "Next A¡í",
                    Step: "Step {1}/{2}",
                    Finish: "Finish"
                }
            },
            is_from_extension: false,
            establish_presence: true,
            test_mode: false
        }
    } else {
        this.debug("TourMyApp::there is already an instance")
    }
    TourMyApp.single_instance.init(B, A);
    return TourMyApp.single_instance
}
TourMyApp.prototype.debug = function () {};
TourMyApp.prototype.establishPresence = function () {
    jQuery123("body").attr("data-tourmyapp", true)
};
TourMyApp.prototype.bind = function (A, B) {
    return jQuery123(this).bind(A, function (C, D) {
        if (D) {
            return B(this, D, C)
        }
        return B(this, {}, C)
    })
};
TourMyApp.prototype.unbind = function (A) {
    return jQuery123(this).unbind(A)
};
TourMyApp.prototype.getHumanReadableStepNumber = function () {
    return this.step + 1
};
TourMyApp.prototype.raiseJSEvent = function (A, B) {
    return jQuery123(this).trigger(A, B)
};
TourMyApp.prototype.init = function (B, A) {
    this.debug("TourMyApp:: init-ing with username and options", B, A);
    this.setOptions(A);
    this.username = B;
    this.record_mode = false;
    this.custom_reference = null;
    this.selector_gadget = null;
    this.cache = {};
    if (this._getOption("establish_presence")) {
        this.establishPresence()
    }
};
TourMyApp.prototype._getOption = function (A) {
    if (this._options[A] !== undefined) {
        return this._options[A]
    }
    return undefined
};
TourMyApp.prototype.setOptions = function (B) {
    if (B === undefined) {
        return
    }
    if (typeof B !== typeof {}) {
        throw "Invalid Param. setOptions requires an object."
    }
    this.debug("TourMyApp::options being set", B);
    for (var A in B) {
        this._options[A] = B[A]
    }
};
TourMyApp.prototype.setUserReference = function (A) {
    this.custom_reference = A
};
TourMyApp.prototype._getLang = function () {
    var A = this._getOption("lang");
    if (!A) {
        return "default"
    }
    return A
};
TourMyApp.prototype._getString = function (D) {
    var A = function (I, H) {
        var F = I;
        for (var G = 1; G < H.length; G++) {
            F = F.replace("{" + G + "}", H[G])
        }
        return F
    };
    var E = this._getOption("translations");
    var C = this._getLang();
    if (!E) {
        return D
    }
    if ((!E[C]) && (!E["default"])) {
        return D
    }
    var B = E[C] || E["default"];
    if ((!B[D]) && (!E["default"][D])) {
        return D
    }
    return A(B[D] || E["default"][D], arguments)
};
TourMyApp.prototype._getStepMessage = function (B) {
    var C = this._getLang();
    var A = B.message_html[C];
    if (A != undefined) {
        return A
    }
    return B.message_html["default"]
};
TourMyApp.prototype._getCustomReference = function () {
    if (this.custom_reference) {
        this._getOption("storage").setItem("tourmyapp_custom_reference", this.custom_reference);
        return this.custom_reference
    }
    if (this._getOption("storage").getItem("tourmyapp_custom_reference")) {
        return this._getOption("storage").getItem("tourmyapp_custom_reference")
    }
    var A = "user-" + Math.round(Math.random() * 100000000);
    this._getOption("storage").setItem("tourmyapp_custom_reference", A);
    return A
};
TourMyApp.prototype.closeAllMessages = function () {
    jQuery123(".tourmyapp").each(function () {
        jQuery123(this).find(".tourmyapp-close").click()
    })
};
TourMyApp.prototype.getParamFromUrl = function (A, D) {
    if (!A.search) {
        return null
    }
    var B = A.search.split("?")[1].split("&");
    for (var C = 0; C < B.length; C++) {
        if (B[C].indexOf(D + "=") == 0) {
            return B[C].split("=")[1]
        }
    }
    return null
};
TourMyApp.prototype.getScenarioFromUrl = function (A) {
    return this.getParamFromUrl(A, "tourmyapp")
};
TourMyApp.prototype.checkIfForcedFromTheUrl = function (A) {
    return this.getParamFromUrl(A, "tourmyapp_force") ? true : false
};
TourMyApp.prototype.getTopPosition = function (B, E, C, A, D) {
    if (A == "page") {
        return this.getPageTopPosition(E, D)
    } else {
        return this.getWidgetTopPosition(B, E, C, D)
    }
};
TourMyApp.prototype.getPageTopPosition = function (C, B) {
    var A = B.height();
    if (C == "top") {
        return jQuery123(window).height() / 6
    }
    if (C == "bottom") {
        return jQuery123(window).height() * 5 / 6 - A
    }
    if (C == "center") {
        return ((jQuery123(window).height() - 100) / 2)
    }
};
TourMyApp.prototype.getWidgetTopPosition = function (B, H, F, G) {
    var D = jQuery123(B).offset()["top"];
    var C = jQuery123(B).outerHeight();
    var A = 16;
    var E = G.height();
    if (H == "top") {
        return D - A - E
    }
    if (H == "bottom") {
        return D + C + A
    }
    if (H == "center") {
        return D + (C / 2) - (E / 2)
    }
};
TourMyApp.prototype.getLeftPosition = function (B, E, C, A, D) {
    if (A == "page") {
        return this.getPageLeftPosition(C, D)
    } else {
        return this.getWidgetLeftPosition(B, E, C, D)
    }
};
TourMyApp.prototype.getPageLeftPosition = function (C, D) {
    var B = D.width();
    var A = jQuery123("body").width();
    if (C == "center") {
        return (A - B) / 2
    }
    if (C == "right") {
        return A * 5 / 6 - B
    }
    if (C == "left") {
        return A / 6
    }
};
TourMyApp.prototype.getWidgetLeftPosition = function (E, K, B, G) {
    var A = jQuery123(E).offset()["left"];
    var J = jQuery123(E).outerWidth();
    var F = A + (J / 2);
    var H = 30;
    var D = 16;
    var C = 10;
    var I = G.width();
    if (K != "center") {
        if (B == "center") {
            return F - (I / 2)
        }
        if (B == "right") {
            return F - (H / 2) - C
        }
        if (B == "left") {
            return (F - I) + C + (H / 2)
        }
    } else {
        if (B == "right") {
            return A + J + D
        }
        if (B == "left") {
            return A - I - D
        }
        if (B == "center") {
            return A + (J / 2) - (I / 2)
        }
    }
};
TourMyApp.prototype.getSelector = function (B) {
    var A = B.selector;
    if (!A) {
        A = "body"
    }
    return A + ":visible"
};
TourMyApp.prototype.goToNextStep = function (C, A, B) {
    this.removeHighLightObject(A);
    jQuery123(B).unbind(".tourmyapp");
    C.remove();
    this.raiseJSEvent("tour_post_step", {
        tour: this.scenario,
        step: this.getHumanReadableStepNumber()
    });
    this.next()
};
TourMyApp.prototype.bindSelectorAction = function (F, D) {
    var C = this;
    var E = D.action;
    var B = D.action_selector;
    var A = this.getSelector(D);
    if (E == "inform") {
        return
    }
    if (!B) {
        B = A
    }
    if (!this.record_mode) {
        jQuery123(B).bind(E + ".tourmyapp", function () {
            C.goToNextStep(F, A, B)
        })
    }
};
TourMyApp.prototype.closeTour = function (A, B, C) {
    this.removeHighLightObject(A);
    jQuery123(B).unbind(".tourmyapp");
    this.sendLog("quit", this.step);
    this.raiseJSEvent("tour_quit", {
        tour: this.scenario,
        step: this.getHumanReadableStepNumber()
    });
    this.finish();
    C.remove();
    return false
};
TourMyApp.prototype.bindDomAction = function (C, A, B) {
    C.find(".tourmyapp-close").click(B);
    if (A) {
        C.find("#tourmyapp-nxt-button").bind("click.tourmyapp", A)
    }
};
TourMyApp.prototype.displayStepNumber = function (C, B, A) {
    C.find(".tourmyapp-toolbar").prepend("<span class='tourmyapp-step-index'>" + this._getString("Step", B, A) + "</span>")
};
TourMyApp.prototype.displayBranding = function (A) {
    A.find(".tourmyapp-branding").show()
};
TourMyApp.prototype.makeLinksOpenInNewWindow = function (A) {
    A.find("a").each(function () {
        jQuery123(this).attr("target", "_blank")
    })
};
TourMyApp.prototype.makeDom = function (B, C) {
    var D = jQuery123("<div id='tourmyapp'><div class='tourmyapp cleanslate' lang='" + this._getLang() + "'><div class='tourmyapp-title'><a title='End tour' href='#' class='tourmyapp-close'></a></div><div class='tourmyapp-content'>" + B + "</div><div><div class='tourmyapp-branding' style='display:none'><a href='http://tourmyapp.com?utm_source=branding' target='_blank' style='position:absolute; bottom:10px;'><img src='" + this._getOption("url") + "/static/images/tourmyapp_powered.png'></a></div><div class='tourmyapp-toolbar'></div><div style='clear:both;'></div></div><div class='tourmyapp-pointer-border'></div><div class='tourmyapp-pointer tourmyapp-pointer-fill'></div></div></div>");
    if (C) {
        var A = jQuery123("<button class='tourmyapp-next_button' id='tourmyapp-nxt-button'></button>");
        A.html(this._getString(C));
        D.find(".tourmyapp-toolbar").append(A)
    }
    if (this._getOption("test_mode")) {
        D.find(".tourmyapp-toolbar").prepend("<span class='tourmyapp-test-mode'>[TEST MODE]</span>");
        D.find(".tourmyapp-test-mode").css({
            "font-weight": "bold",
            "font-size": "small",
            padding: "2px",
            color: "red",
            "margin-left": "3px",
            display: "inline"
        })
    }
    this.makeLinksOpenInNewWindow(D.find(".tourmyapp-content"));
    D.css({
        position: "absolute"
    }).hide();
    jQuery123("body").append(D);
    return D
};
TourMyApp.prototype.getWindowHeight = function () {
    return (window.innerHeight && window.innerHeight < jQuery123(window).height()) ? window.innerHeight : jQuery123(window).height()
};
TourMyApp.prototype.getWindowScrollPosition = function () {
    return document.documentElement.scrollTop || document.body.scrollTop
};
TourMyApp.prototype.getWindow = function () {
    return window
};
TourMyApp.prototype.isElementInViewPort = function (D) {
    var B = this.getWindowScrollPosition();
    var C = D.offset().top;
    var A = this.getWindowHeight();
    return C > B && (D.height() + C) < (B + A)
};
TourMyApp.prototype.bringToViewport = function (A) {
    if (!this.isElementInViewPort(A)) {
        jQuery123(this.getWindow()).scrollTop(A.offset().top - (jQuery123(this.getWindow()).height() / 2))
    }
};
TourMyApp.prototype.animateDom = function (A, B) {
    if (this.record_mode) {
        A.show();
        B();
        return
    }
    A.fadeIn(function () {
        B()
    })
};
TourMyApp.prototype.setStyle = function (F, C) {
    for (var A in C) {
        var E = C[A];
        for (var B in E) {
            var D = A.replace(/:/g, ".");
            F.find(D).css(B, E[B])
        }
    }
};
TourMyApp.prototype.waitForElement = function (A, B, F, G) {
    var E = this;
    var D = 2000;
    G = G || 10000;
    var C = function () {
        if (!G) {
            return
        }
        setTimeout(function () {
            if (jQuery123(A).length == 0) {
                C()
            } else {
                E.showStep(B, F);
                return
            }
            G = G - D
        }, D)
    };
    C()
};
TourMyApp.prototype.positionDom = function (H, D, B) {
    var A = B.split("-")[0];
    var C = B.split("-")[1];
    var G = B.split("-")[2];
    var F = this.getTopPosition(D, C, G, A, H);
    var E = this.getLeftPosition(D, C, G, A, H);
    if (A == "widget") {
        H.find(".tourmyapp-pointer").add(H.find(".tourmyapp-pointer-border")).addClass("tourmyapp-pointer-" + C);
        if (G != "center") {
            H.find(".tourmyapp-pointer").add(H.find(".tourmyapp-pointer-border")).addClass("tourmyapp-pointer-" + G)
        }
        if (C == "center") {
            H.find(".tourmyapp-pointer").css("top", (H.height() / 2 - 15));
            H.find(".tourmyapp-pointer-border").css("top", (H.height() / 2 - 16))
        }
    }
    H.css({
        left: E
    });
    H.css({
        top: F
    })
};
TourMyApp.prototype.getActionSelector = function (A) {
    return A.action_selector ? A.action_selector : this.getSelector(A)
};
TourMyApp.prototype.renderMessageBox = function (C, E, B, A) {
    var D = this;
    var F = this.makeDom(C, E);
    this.positionDom(F, B, A);
    if (this.style) {
        this.setStyle(F, this.style)
    }
    this.animateDom(F, function () {
        D.bringToViewport(F)
    });
    return F
};
TourMyApp.prototype.getButtonText = function (C, B) {
    var A = null;
    if (C == "inform") {
        if (!B) {
            A = "Next"
        } else {
            A = "Finish"
        }
    }
    return A
};
TourMyApp.prototype.showQuickMessage = function (E, D, B, A) {
    var C = this;
    var F = function (H) {
        jQuery123(B).unbind(".tourmyapp");
        H.remove();
        H = null
    };
    var G = this.renderMessageBox(E, D, B, A);
    this.bindDomAction(G, function () {
        F(G)
    }, function () {
        F(G)
    });
    return G
};
TourMyApp.prototype.showStep = function (A, J) {
    var H = this;
    var B = this.getSelector(A);
    var C = A.action;
    var I = this.getActionSelector(A);
    this.debug(B);
    if (H._getOption("step_checker").shouldSkipStep(A)) {
        H.next();
        return
    }
    var E = function (L, M, N) {
        return function () {
            return H.closeTour(L, M, N)
        }
    };
    if (H._getOption("step_checker").shouldShowMessage(A)) {
        var K = A.step_meta["condition"]["message"];
        var D = this.renderMessageBox(K, "Okay", null, "page-center-center");
        this.bindDomAction(D, E("", "", D), E("", "", D));
        return
    }
    H.raiseJSEvent("tour_pre_step", {
        tour: H.scenario,
        step: H.getHumanReadableStepNumber()
    });
    if (jQuery123(B).length == 0) {
        H.waitForElement(B, A, J);
        return
    }
    H.highLightObject(B);
    var F = this.getButtonText(C, J);
    var D = this.renderMessageBox(this._getStepMessage(A), F, B, A.position || "widget-bottom-right");
    if ((H.data) && (H.data.length)) {
        this.displayStepNumber(D, H.getHumanReadableStepNumber(), H.data.length)
    }
    if (H._getOption("display_branding")) {
        this.displayBranding(D)
    }
    this.bindSelectorAction(D, A);
    var G = null;
    if ((!this.record_mode) && (C == "inform")) {
        G = function () {
            H.goToNextStep(D, B, "#tourmyapp-nxt-button")
        }
    }
    this.bindDomAction(D, G, E(B, I, D))
};
TourMyApp.prototype.highLightObject = function (A) {
    jQuery123(A).addClass("tourmyapp-selected")
};
TourMyApp.prototype.removeHighLightObject = function (A) {
    jQuery123(A).removeClass("tourmyapp-selected")
};
TourMyApp.prototype.next = function () {
    var B = this;
    this.step++;
    this._getOption("storage").setItem("tourmyapp_step", this.step);
    if (this.step >= this.data.length) {
        B.sendLog("next_step", B.step);
        B.sendLog("finish", B.step);
        B.raiseJSEvent("tour_finish", {
            tour: B.scenario
        });
        this.finish();
        return
    }
    var A = this.findIfLastStep();
    setTimeout(function () {
        B.sendLog("next_step", B.step);
        B.showStep(B.data[B.step], A)
    }, 10)
};
TourMyApp.prototype.finish = function () {
    this.purgeMemory()
};
TourMyApp.prototype.isExtentionActive = function () {
    return this._getOption("is_from_extension")
};
TourMyApp.prototype.inTestMode = function () {
    return this._getOption("test_mode")
};
TourMyApp.prototype.sendLog = function (C, B) {
    if (this.isExtentionActive()) {
        return
    }
    if (this.inTestMode()) {
        return
    }
    if (this.record_mode) {
        return
    }
    var A = this._getCustomReference();
    this._getOption("repository").sendLogData(this.scenario, A, C, B, function () {})
};
TourMyApp.prototype.getStartingStep = function () {
    var A = 0;
    if (this._getOption("storage").getItem("tourmyapp_step")) {
        A = parseInt(this._getOption("storage").getItem("tourmyapp_step"))
    }
    return A
};
TourMyApp.prototype.findIfLastStep = function () {
    var A = false;
    if (this.data.length - this.step == 1) {
        A = true
    }
    return A
};
TourMyApp.prototype.getScenarioFromStorage = function () {
    return this._getOption("storage").getItem("tourmyapp_scenario")
};
TourMyApp.prototype.checkIfForcedFromStorage = function () {
    return this._getOption("storage").getItem("tourmyapp_force") ? true : false
};
TourMyApp.prototype.startTour = function (B, A) {
    this.scenario = B;
    if (A) {
        this.sendLog("start", 0)
    }
    this.closeAllMessages();
    this.step = this.getStartingStep();
    if (this.step >= this.data.length) {
        return
    }
    var C = this.findIfLastStep();
    this.running = true;
    this.showStep(this.data[this.step], C)
};
TourMyApp.prototype.preload = function (A) {
    var B = this;
    this._getOption("repository").fetchTourDetails(A, this._getCustomReference(), true, function (C) {
        B.cache[A] = C
    })
};
TourMyApp.prototype.fetchData = function (B, A, E) {
    var D = this;
    var C = function (F) {
        D.style = F.style;
        D.data = F.step;
        if (F.display_branding !== undefined) {
            D.setOptions({
                display_branding: F.display_branding
            })
        }
        D.startTour(B, A)
    };
    if (this.cache[B]) {
        C(this.cache[B])
    } else {
        this._getOption("repository").fetchTourDetails(B, this._getCustomReference(), E, function (F) {
            D.cache[B] = F;
            C(F)
        })
    }
};
TourMyApp.prototype.purgeMemory = function () {
    this._getOption("storage").removeItem("tourmyapp_step");
    this._getOption("storage").removeItem("tourmyapp_scenario");
    this._getOption("storage").removeItem("tourmyapp_force")
};
TourMyApp.prototype.run = function (B, E, A) {
    var D = this;
    var C = true;
    if (A) {
        C = false
    }
    if (C) {
        D.raiseJSEvent("tour_start", {
            tour: B
        });
        D.purgeMemory()
    }
    if (E) {
        this._getOption("storage").setItem("tourmyapp_force", "true")
    }
    if (B) {
        this._getOption("storage").setItem("tourmyapp_scenario", B);
        this.fetchData(B, C, E)
    }
};
TourMyApp.prototype.getHrefFromWindowObject = function (A) {
    return A.location.href
};
TourMyApp.prototype.isInTourBuilder = function (E, A) {
    var B = function () {
        return E === A
    };
    if (B()) {
        return false
    }
    try {
        var D = this.getHrefFromWindowObject(E);
        if (D.indexOf(this._getOption("url")) > -1) {
            return true
        } else {
            return false
        }
    } catch (C) {
        return true
    }
};
TourMyApp.prototype.start = function (E, D) {
    this.debug("TourMyApp:storage status on start", document.cookie);
    if (this.isInTourBuilder(window.top, window.self)) {
        this.debug("TourMyApp:is in record mode");
        this.record_mode = true;
        return
    }
    var C = this.getScenarioFromUrl(window.location);
    var B = this.checkIfForcedFromTheUrl(window.location);
    if (C) {
        this.debug("TourMyApp:picked scenario from url", C);
        this.run(C, B);
        return
    }
    var F = this.getScenarioFromStorage();
    var A = this.checkIfForcedFromStorage();
    if (F) {
        this.debug("TourMyApp:picked scenario from storage", F);
        this.run(F, A, true);
        return
    }
    D = D ? true : false;
    if (E) {
        this.debug("picked scenario from start", E);
        this.run(E, D)
    }
};

function TourMyAppRecorder(A) {
    this.tourmyapp = A
}
TourMyAppRecorder.prototype.postToIFrame = function (B) {
    var A = JSON.stringify(B);
    window.top.postMessage(A, "*")
};
TourMyAppRecorder.prototype.switchToRecordMode = function () {
    var A = this;
    this.record_mode = true;
    this.receiveMessages();
    A.importCSS(A.tourmyapp._getOption("url") + "/static/utils/utils.css");
    A.importJS(A.tourmyapp._getOption("url") + "/static/utils/utils.js", "SelectorGadget", function () {
        A.postToIFrame({
            message: "inspection_ready"
        })
    })
};
TourMyAppRecorder.prototype.getHrefFromWindowObject = function (A) {
    return A.location.href
};
TourMyAppRecorder.prototype.isInTourBuilder = function (E, A) {
    var B = function () {
        return E === A
    };
    if (B()) {
        return false
    }
    try {
        var D = this.getHrefFromWindowObject(E);
        if (D.indexOf(this.tourmyapp._getOption("url")) > -1) {
            return true
        } else {
            return false
        }
    } catch (C) {
        return true
    }
};
TourMyAppRecorder.prototype.is_screen_step = function (A) {
    return A.position.indexOf("page-") == 0
};
TourMyAppRecorder.prototype.should_propagate_message = function (A) {
    if ((A.message == "show_step") && (this.is_screen_step(A.step))) {
        return false
    }
    return true
};
TourMyAppRecorder.prototype.receiveMessages = function () {
    var A = this;

    function B(C) {
        var D = A.decodeMessage(C.data);
        if (D.message == "show_step") {
            A.tourmyapp.showStep(D.step)
        }
        if (D.message == "inspect_selector") {
            A.record_for = "update_selector";
            A.inspectElement()
        }
        if (D.message == "inspect_action_selector") {
            A.record_for = "update_action_selector";
            A.inspectElement()
        }
        if (D.message == "cancel_selector") {
            A.cancelElement()
        }
        if (D.message == "hide_step") {
            A.tourmyapp.closeAllMessages()
        }
        if (A.should_propagate_message(D)) {
            jQuery123("iframe").each(function () {
                this.contentWindow.postMessage(C.data, "*")
            })
        }
    }
    window.addEventListener("message", B, false)
};
TourMyAppRecorder.prototype.cancelElement = function () {
    this.selector_gadget.unbind();
    this.selector_gadget.setMode("browse")
};
TourMyAppRecorder.prototype.importJS = function (F, A, E) {
    var D = this;
    var C = document.createElement("script");
    C.setAttribute("type", "text/javascript");
    C.setAttribute("src", F);
    if (E) {
        D.wait_for_script_load(A, E)
    }
    var B = document.getElementsByTagName("head")[0];
    if (B) {
        B.appendChild(C)
    } else {
        document.body.appendChild(C)
    }
};
TourMyAppRecorder.prototype.importCSS = function (B, A, E) {
    var D = document.createElement("link");
    D.setAttribute("rel", "stylesheet");
    D.setAttribute("type", "text/css");
    D.setAttribute("media", "screen");
    D.setAttribute("href", B);
    if (E) {
        wait_for_script_load(A, E)
    }
    var C = document.getElementsByTagName("head")[0];
    if (C) {
        C.appendChild(D)
    } else {
        document.body.appendChild(D)
    }
};
TourMyAppRecorder.prototype.wait_for_script_load = function (look_for, callback) {
    var interval = setInterval(function () {
        if (eval("typeof " + look_for) != "undefined") {
            clearInterval(interval);
            callback()
        }
    }, 50)
};
TourMyAppRecorder.prototype.inspectElement = function () {
    var A = this;
    A.tourmyapp.closeAllMessages();
    if (typeof (A.selector_gadget) == "undefined" || A.selector_gadget == null) {
        A.selector_gadget = new SelectorGadget();
        A.selector_gadget.addPathChangeSubscribers(function (C) {
            var B = {
                message: A.record_for,
                selector: C
            };
            A.postToIFrame(B);
            A.record_for = null
        })
    } else {
        if (A.selector_gadget.unbound) {
            A.selector_gadget.rebind()
        }
    }
    A.selector_gadget.setMode("interactive")
};
TourMyAppRecorder.prototype.decodeMessage = function (A) {
    return JSON.parse(A)
};