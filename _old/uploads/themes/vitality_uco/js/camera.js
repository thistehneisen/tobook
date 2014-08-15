(function (e) {
    e.fn.camera = function (t, n) {
        function s() {
            if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i)) {
                return true
            }
        }

        function I() {
            var t = e(b).width();
            e("li", b).removeClass("camera_visThumb");
            e("li", b).each(function () {
                var n = e(this).position(),
                    r = e("ul", b).outerWidth(),
                    i = e("ul", b).offset().left,
                    s = e("> div", b).offset().left,
                    o = s - i;
                e(".camera_prevThumbs", nt).removeClass("hideNav").css("visibility", "visible");
                e(".camera_nextThumbs", nt).removeClass("hideNav").css("visibility", "visible");
                var u = n.left,
                    a = n.left + e(this).width();
                if (a - o <= t && u - o >= 0) {
                    e(this).addClass("camera_visThumb")
                }
            })
        }

        function U() {
            if (t.lightbox == "mediaboxck" && typeof Mediabox != "undefined") {
                Mediabox.scanPage()
            } else if (t.lightbox == "squeezebox") {
                SqueezeBox.initialize({});
                SqueezeBox.assign($$("a.camera_link[rel=lightbox]"), {})
            }
        }

        function z() {
            w = o.width();
            if (t.height.indexOf("%") != -1) {
                var e = Math.round(w / (100 / parseFloat(t.height)));
                if (t.minHeight != "" && e < parseFloat(t.minHeight)) {
                    E = parseFloat(t.minHeight)
                } else {
                    E = e
                }
                o.css({
                    height: E
                })
            } else if (t.height == "auto") {
                E = o.height()
            } else {
                E = parseFloat(t.height);
                o.css({
                    height: E
                })
            }
        }

        function W() {
            function r() {
                w = o.width();
                z();
                e(".camerarelative", c).css({
                    width: w,
                    height: E
                });
                e(".imgLoaded", c).each(function () {
                    var n = e(this),
                        r = n.attr("width"),
                        i = n.attr("height"),
                        s = n.index(),
                        o, u, a = n.attr("data-alignment"),
                        f = n.attr("data-portrait");
                    if (typeof a === "undefined" || a === false || a === "") {
                        a = t.alignment
                    }
                    if (typeof f === "undefined" || f === false || f === "") {
                        f = t.portrait
                    }
                    if (f == false || f == "false") {
                        if (r / i < w / E) {
                            var l = w / r;
                            var c = Math.abs(E - i * l) * .5;
                            switch (a) {
                            case "topLeft":
                                o = 0;
                                break;
                            case "topCenter":
                                o = 0;
                                break;
                            case "topRight":
                                o = 0;
                                break;
                            case "centerLeft":
                                o = "-" + c + "px";
                                break;
                            case "center":
                                o = "-" + c + "px";
                                break;
                            case "centerRight":
                                o = "-" + c + "px";
                                break;
                            case "bottomLeft":
                                o = "-" + c * 2 + "px";
                                break;
                            case "bottomCenter":
                                o = "-" + c * 2 + "px";
                                break;
                            case "bottomRight":
                                o = "-" + c * 2 + "px";
                                break
                            }
                            if (t.fullpage == true) {
                                n.css({
                                    height: i * l,
                                    "margin-left": 0,
                                    "margin-right": 0,
                                    "margin-top": o,
                                    position: "absolute",
                                    visibility: "visible",
                                    left: 0,
                                    width: w
                                })
                            } else {
                                n.css({
                                    height: i * l,
                                    "margin-left": 0,
                                    "margin-right": 0,
                                    "margin-top": o,
                                    position: "absolute",
                                    visibility: "visible",
                                    width: w
                                })
                            }
                        } else {
                            var l = E / i;
                            var c = Math.abs(w - r * l) * .5;
                            switch (a) {
                            case "topLeft":
                                u = 0;
                                break;
                            case "topCenter":
                                u = "-" + c + "px";
                                break;
                            case "topRight":
                                u = "-" + c * 2 + "px";
                                break;
                            case "centerLeft":
                                u = 0;
                                break;
                            case "center":
                                u = "-" + c + "px";
                                break;
                            case "centerRight":
                                u = "-" + c * 2 + "px";
                                break;
                            case "bottomLeft":
                                u = 0;
                                break;
                            case "bottomCenter":
                                u = "-" + c + "px";
                                break;
                            case "bottomRight":
                                u = "-" + c * 2 + "px";
                                break
                            }
                            n.css({
                                height: E,
                                "margin-left": u,
                                "margin-right": u,
                                "margin-top": 0,
                                position: "absolute",
                                visibility: "visible",
                                width: r * l
                            })
                        }
                    } else {
                        if (r / i < w / E) {
                            var l = E / i;
                            var c = Math.abs(w - r * l) * .5;
                            switch (a) {
                            case "topLeft":
                                u = 0;
                                break;
                            case "topCenter":
                                u = c + "px";
                                break;
                            case "topRight":
                                u = c * 2 + "px";
                                break;
                            case "centerLeft":
                                u = 0;
                                break;
                            case "center":
                                u = c + "px";
                                break;
                            case "centerRight":
                                u = c * 2 + "px";
                                break;
                            case "bottomLeft":
                                u = 0;
                                break;
                            case "bottomCenter":
                                u = c + "px";
                                break;
                            case "bottomRight":
                                u = c * 2 + "px";
                                break
                            }
                            n.css({
                                height: E,
                                "margin-left": u,
                                "margin-right": u,
                                "margin-top": 0,
                                position: "absolute",
                                visibility: "visible",
                                width: r * l
                            })
                        } else {
                            var l = w / r;
                            var c = Math.abs(E - i * l) * .5;
                            switch (a) {
                            case "topLeft":
                                o = 0;
                                break;
                            case "topCenter":
                                o = 0;
                                break;
                            case "topRight":
                                o = 0;
                                break;
                            case "centerLeft":
                                o = c + "px";
                                break;
                            case "center":
                                o = c + "px";
                                break;
                            case "centerRight":
                                o = c + "px";
                                break;
                            case "bottomLeft":
                                o = c * 2 + "px";
                                break;
                            case "bottomCenter":
                                o = c * 2 + "px";
                                break;
                            case "bottomRight":
                                o = c * 2 + "px";
                                break
                            }
                            n.css({
                                height: i * l,
                                "margin-left": 0,
                                "margin-right": 0,
                                "margin-top": o,
                                position: "absolute",
                                visibility: "visible",
                                width: w
                            })
                        }
                    }
                })
            }
            var n;
            if (q == true) {
                clearTimeout(n);
                n = setTimeout(r, 200)
            } else {
                r()
            }
            q = true
        }

        function it(e) {
            for (var t, n, r = e.length; r; t = parseInt(Math.random() * r), n = e[--r], e[r] = e[t], e[t] = n);
            return e
        }

        function st(e) {
            return Math.ceil(e) == Math.floor(e)
        }

        function pt() {
            if (e(b).length) {
                var n;
                if (!e(y).length) {
                    e(b).append("<div />");
                    e(b).before('<div class="camera_prevThumbs hideNav"><div></div></div>').before('<div class="camera_nextThumbs hideNav"><div></div></div>');
                    e("> div", b).append("<ul />");
                    e("ul", b).width(_.length * (parseInt(t.thumbwidth) + 2));
                    e("ul", b).height(parseInt(t.thumbheight));
                    e.each(_, function (t, n) {
                        if (e("> div", l).eq(t).attr("data-thumb") != "") {
                            var r = e("> div", l).eq(t).attr("data-thumb"),
                                i = new Image;
                            i.src = r;
                            e("ul", b).append('<li class="pix_thumb pix_thumb_' + t + '" />');
                            e("li.pix_thumb_" + t, b).append(e(i).attr("class", "camera_thumb"))
                        }
                    })
                } else {
                    e.each(_, function (t, n) {
                        if (e("> div", l).eq(t).attr("data-thumb") != "") {
                            var r = e("> div", l).eq(t).attr("data-thumb"),
                                i = new Image;
                            i.src = r;
                            e("li.pag_nav_" + t, y).append(e(i).attr("class", "camera_thumb").css({
                                position: "absolute"
                            }).animate({
                                opacity: 0
                            }, 0));
                            e("li.pag_nav_" + t + " > img", y).after('<div class="thumb_arrow" />');
                            e("li.pag_nav_" + t + " > .thumb_arrow", y).animate({
                                opacity: 0
                            }, 0)
                        }
                    });
                    o.css({
                        marginBottom: e(y).outerHeight()
                    })
                }
            } else if (!e(b).length && e(y).length) {
                o.css({
                    marginBottom: e(y).outerHeight()
                })
            }
        }

        function vt() {
            if (e(b).length && !e(y).length) {
                var t = e(b).outerWidth(),
                    n = e("ul > li", b).outerWidth(),
                    r = e("li.cameracurrent", b).length ? e("li.cameracurrent", b).position() : "",
                    i = e("ul > li", b).length * e("ul > li", b).outerWidth(),
                    s = e("ul", b).offset().left,
                    u = e("> div", b).offset().left,
                    a;
                if (s < 0) {
                    a = "-" + (u - s)
                } else {
                    a = u - s
                } if (dt == true) {
                    e("ul", b).width(e("ul > li", b).length * e("ul > li", b).outerWidth());
                    if (e(b).length && !e(y).lenght) {
                        o.css({
                            marginBottom: e(b).outerHeight()
                        })
                    }
                    I();
                    if (e(b).length && !e(y).lenght) {
                        o.css({
                            marginBottom: e(b).outerHeight()
                        })
                    }
                }
                dt = false;
                var f = e("li.cameracurrent", b).length ? r.left : "",
                    l = e("li.cameracurrent", b).length ? r.left + e("li.cameracurrent", b).outerWidth() : "";
                if (f < e("li.cameracurrent", b).outerWidth()) {
                    f = 0
                }
                if (l - a > t) {
                    if (f + t < i) {
                        e("ul", b).animate({
                            "margin-left": "-" + f + "px"
                        }, 500, I)
                    } else {
                        e("ul", b).animate({
                            "margin-left": "-" + (e("ul", b).outerWidth() - t) + "px"
                        }, 500, I)
                    }
                } else if (f - a < 0) {
                    e("ul", b).animate({
                        "margin-left": "-" + f + "px"
                    }, 500, I)
                } else {
                    e("ul", b).css({
                        "margin-left": "auto",
                        "margin-right": "auto"
                    });
                    setTimeout(I, 100)
                }
            }
        }

        function mt() {
            ft = 0;
            var n = e(".camera_bar_cont", nt).width(),
                r = e(".camera_bar_cont", nt).height();
            if (a != "pie") {
                switch (tt) {
                case "leftToRight":
                    e("#" + f).css({
                        right: n
                    });
                    break;
                case "rightToLeft":
                    e("#" + f).css({
                        left: n
                    });
                    break;
                case "topToBottom":
                    e("#" + f).css({
                        bottom: r
                    });
                    break;
                case "bottomToTop":
                    e("#" + f).css({
                        top: r
                    });
                    break
                }
            } else {
                ct.clearRect(0, 0, t.pieDiameter, t.pieDiameter)
            }
        }

        function gt(n) {
            l.addClass("camerasliding");
            Y = false;
            var r = parseFloat(e("div.cameraSlide.cameracurrent", c).index());
            if (n > 0) {
                var i = n - 1
            } else if (r == D - 1) {
                var i = 0
            } else {
                var i = r + 1
            }
            var h = e(".cameraSlide:eq(" + i + ")", c);
            var p = e(".cameraSlide:eq(" + (i + 1) + ")", c).addClass("cameranext");
            if (r != i + 1) {
                p.hide()
            }
            e(".cameraContent", u).fadeOut(600);
            e(".camera_caption", u).show();
            e(".camerarelative", h).append(e("> div ", l).eq(i).find("> div.camera_effected"));
            e(".camera_target_content .cameraContent:eq(" + i + ")", o).append(e("> div ", l).eq(i).find("> div"));
            if (!e(".imgLoaded", h).length) {
                var d = T[i];
                var v = new Image;
                v.src = d + "?" + (new Date).getTime();
                h.css("visibility", "hidden");
                h.prepend(e(v).attr("class", "imgLoaded").css("visibility", "hidden"));
                var m, g;
                if (!e(v).get(0).complete || m == "0" || g == "0" || typeof m === "undefined" || m === false || typeof g === "undefined" || g === false) {
                    e(".camera_loader", o).delay(500).fadeIn(400);
                    v.onload = function () {
                        m = v.naturalWidth;
                        g = v.naturalHeight;
                        e(v).attr("data-alignment", M[i]).attr("data-portrait", O[i]);
                        e(v).attr("width", m);
                        e(v).attr("height", g);
                        c.find(".cameraSlide_" + i).css("visibility", "visible");
                        W();
                        gt(i + 1);
                        if (e(".camera_loader", o).is(":visible")) {
                            e(".camera_loader", o).fadeOut(400)
                        } else {
                            e(".camera_loader", o).css({
                                visibility: "hidden"
                            });
                            e(".camera_loader", o).fadeOut(400, function () {
                                e(".camera_loader", o).css({
                                    visibility: "visible"
                                })
                            })
                        }
                    }
                }
            } else {
                if (T.length > i + 1 && !e(".imgLoaded", p).length) {
                    var S = T[i + 1];
                    var x = new Image;
                    x.src = S + "?" + (new Date).getTime();
                    p.prepend(e(x).attr("class", "imgLoaded").css("visibility", "hidden"));
                    x.onload = function () {
                        m = x.naturalWidth;
                        g = x.naturalHeight;
                        e(x).attr("data-alignment", M[i + 1]).attr("data-portrait", O[i + 1]);
                        e(x).attr("width", m);
                        e(x).attr("height", g);
                        W()
                    }
                }
                t.onLoaded.call(this);
                var N = t.rows,
                    C = t.cols,
                    k = 1,
                    L = 0,
                    A, _, P, H, j, F = new Array("simpleFade", "curtainTopLeft", "curtainTopRight", "curtainBottomLeft", "curtainBottomRight", "curtainSliceLeft", "curtainSliceRight", "blindCurtainTopLeft", "blindCurtainTopRight", "blindCurtainBottomLeft", "blindCurtainBottomRight", "blindCurtainSliceBottom", "blindCurtainSliceTop", "stampede", "mosaic", "mosaicReverse", "mosaicRandom", "mosaicSpiral", "mosaicSpiralReverse", "topLeftBottomRight", "bottomRightTopLeft", "bottomLeftTopRight", "topRightBottomLeft", "scrollLeft", "scrollRight", "scrollTop", "scrollBottom", "scrollHorz");
                marginLeft = 0, marginTop = 0, opacityOnGrid = 0;
                if (t.opacityOnGrid == true) {
                    opacityOnGrid = 0
                } else {
                    opacityOnGrid = 1
                }
                var I = e(" > div", l).eq(i).attr("data-fx");
                if (s() && t.mobileFx != "" && t.mobileFx != "default") {
                    H = t.mobileFx
                } else {
                    if (typeof I !== "undefined" && I !== false && I !== "default") {
                        H = I
                    } else {
                        H = t.fx
                    }
                } if (H == "random") {
                    H = it(F);
                    H = H[0]
                } else {
                    H = H;
                    if (H.indexOf(",") > 0) {
                        H = H.replace(/ /g, "");
                        H = H.split(",");
                        H = it(H);
                        H = H[0]
                    }
                }
                dataEasing = e(" > div", l).eq(i).attr("data-easing");
                mobileEasing = e(" > div", l).eq(i).attr("data-mobileEasing");
                if (s() && t.mobileEasing != "" && t.mobileEasing != "default") {
                    if (typeof mobileEasing !== "undefined" && mobileEasing !== false && mobileEasing !== "default") {
                        j = mobileEasing
                    } else {
                        j = t.mobileEasing
                    }
                } else {
                    if (typeof dataEasing !== "undefined" && dataEasing !== false && dataEasing !== "default") {
                        j = dataEasing
                    } else {
                        j = t.easing
                    }
                }
                A = e(" > div", l).eq(i).attr("data-slideOn");
                if (typeof A !== "undefined" && A !== false) {
                    q = A
                } else {
                    if (t.slideOn == "random") {
                        var q = new Array("next", "prev");
                        q = it(q);
                        q = q[0]
                    } else {
                        q = t.slideOn
                    }
                }
                var R = e(" > div", l).eq(i).attr("data-time");
                if (typeof R !== "undefined" && R !== false && R !== "") {
                    _ = parseFloat(R)
                } else {
                    _ = t.time
                }
                var U = e(" > div", l).eq(i).attr("data-transPeriod");
                if (typeof U !== "undefined" && U !== false && U !== "") {
                    P = parseFloat(U)
                } else {
                    P = t.transPeriod
                } if (!e(l).hasClass("camerastarted")) {
                    H = "simpleFade";
                    q = "next";
                    j = "";
                    P = 400;
                    e(l).addClass("camerastarted")
                }
                switch (H) {
                case "simpleFade":
                    C = 1;
                    N = 1;
                    break;
                case "curtainTopLeft":
                    if (t.slicedCols == 0) {
                        C = t.cols
                    } else {
                        C = t.slicedCols
                    }
                    N = 1;
                    break;
                case "curtainTopRight":
                    if (t.slicedCols == 0) {
                        C = t.cols
                    } else {
                        C = t.slicedCols
                    }
                    N = 1;
                    break;
                case "curtainBottomLeft":
                    if (t.slicedCols == 0) {
                        C = t.cols
                    } else {
                        C = t.slicedCols
                    }
                    N = 1;
                    break;
                case "curtainBottomRight":
                    if (t.slicedCols == 0) {
                        C = t.cols
                    } else {
                        C = t.slicedCols
                    }
                    N = 1;
                    break;
                case "curtainSliceLeft":
                    if (t.slicedCols == 0) {
                        C = t.cols
                    } else {
                        C = t.slicedCols
                    }
                    N = 1;
                    break;
                case "curtainSliceRight":
                    if (t.slicedCols == 0) {
                        C = t.cols
                    } else {
                        C = t.slicedCols
                    }
                    N = 1;
                    break;
                case "blindCurtainTopLeft":
                    if (t.slicedRows == 0) {
                        N = t.rows
                    } else {
                        N = t.slicedRows
                    }
                    C = 1;
                    break;
                case "blindCurtainTopRight":
                    if (t.slicedRows == 0) {
                        N = t.rows
                    } else {
                        N = t.slicedRows
                    }
                    C = 1;
                    break;
                case "blindCurtainBottomLeft":
                    if (t.slicedRows == 0) {
                        N = t.rows
                    } else {
                        N = t.slicedRows
                    }
                    C = 1;
                    break;
                case "blindCurtainBottomRight":
                    if (t.slicedRows == 0) {
                        N = t.rows
                    } else {
                        N = t.slicedRows
                    }
                    C = 1;
                    break;
                case "blindCurtainSliceTop":
                    if (t.slicedRows == 0) {
                        N = t.rows
                    } else {
                        N = t.slicedRows
                    }
                    C = 1;
                    break;
                case "blindCurtainSliceBottom":
                    if (t.slicedRows == 0) {
                        N = t.rows
                    } else {
                        N = t.slicedRows
                    }
                    C = 1;
                    break;
                case "stampede":
                    L = "-" + P;
                    break;
                case "mosaic":
                    L = t.gridDifference;
                    break;
                case "mosaicReverse":
                    L = t.gridDifference;
                    break;
                case "mosaicRandom":
                    break;
                case "mosaicSpiral":
                    L = t.gridDifference;
                    k = 1.7;
                    break;
                case "mosaicSpiralReverse":
                    L = t.gridDifference;
                    k = 1.7;
                    break;
                case "topLeftBottomRight":
                    L = t.gridDifference;
                    k = 6;
                    break;
                case "bottomRightTopLeft":
                    L = t.gridDifference;
                    k = 6;
                    break;
                case "bottomLeftTopRight":
                    L = t.gridDifference;
                    k = 6;
                    break;
                case "topRightBottomLeft":
                    L = t.gridDifference;
                    k = 6;
                    break;
                case "scrollLeft":
                    C = 1;
                    N = 1;
                    break;
                case "scrollRight":
                    C = 1;
                    N = 1;
                    break;
                case "scrollTop":
                    C = 1;
                    N = 1;
                    break;
                case "scrollBottom":
                    C = 1;
                    N = 1;
                    break;
                case "scrollHorz":
                    C = 1;
                    N = 1;
                    break
                }
                var z = 0;
                var J = N * C;
                var K = w - Math.floor(w / C) * C;
                var Q = E - Math.floor(E / N) * N;
                var G;
                var et;
                var st = 0;
                var ot = 0;
                var ut = new Array;
                var at = new Array;
                var ht = new Array;
                while (z < J) {
                    ut.push(z);
                    at.push(z);
                    B.append('<div class="cameraappended" style="display:none; overflow:hidden; position:absolute; z-index:1000" />');
                    var pt = e(".cameraappended:eq(" + z + ")", c);
                    if (H == "scrollLeft" || H == "scrollRight" || H == "scrollTop" || H == "scrollBottom" || H == "scrollHorz") {
                        Z.eq(i).clone().show().appendTo(pt)
                    } else {
                        if (q == "next") {
                            Z.eq(i).clone().show().appendTo(pt)
                        } else {
                            Z.eq(r).clone().show().appendTo(pt)
                        }
                    } if (z % C < K) {
                        G = 1
                    } else {
                        G = 0
                    } if (z % C == 0) {
                        st = 0
                    }
                    if (Math.floor(z / C) < Q) {
                        et = 1
                    } else {
                        et = 0
                    }
                    pt.css({
                        height: Math.floor(E / N + et + 1),
                        left: st,
                        top: ot,
                        width: Math.floor(w / C + G + 1)
                    });
                    e("> .cameraSlide", pt).css({
                        height: E,
                        "margin-left": "-" + st + "px",
                        "margin-top": "-" + ot + "px",
                        width: w
                    });
                    st = st + pt.width() - 1;
                    if (z % C == C - 1) {
                        ot = ot + pt.height() - 1
                    }
                    z++
                }
                switch (H) {
                case "curtainTopLeft":
                    break;
                case "curtainBottomLeft":
                    break;
                case "curtainSliceLeft":
                    break;
                case "curtainTopRight":
                    ut = ut.reverse();
                    break;
                case "curtainBottomRight":
                    ut = ut.reverse();
                    break;
                case "curtainSliceRight":
                    ut = ut.reverse();
                    break;
                case "blindCurtainTopLeft":
                    break;
                case "blindCurtainBottomLeft":
                    ut = ut.reverse();
                    break;
                case "blindCurtainSliceTop":
                    break;
                case "blindCurtainTopRight":
                    break;
                case "blindCurtainBottomRight":
                    ut = ut.reverse();
                    break;
                case "blindCurtainSliceBottom":
                    ut = ut.reverse();
                    break;
                case "stampede":
                    ut = it(ut);
                    break;
                case "mosaic":
                    break;
                case "mosaicReverse":
                    ut = ut.reverse();
                    break;
                case "mosaicRandom":
                    ut = it(ut);
                    break;
                case "mosaicSpiral":
                    var dt = N / 2,
                        yt, bt, wt, Et = 0;
                    for (wt = 0; wt < dt; wt++) {
                        bt = wt;
                        for (yt = wt; yt < C - wt - 1; yt++) {
                            ht[Et++] = bt * C + yt
                        }
                        yt = C - wt - 1;
                        for (bt = wt; bt < N - wt - 1; bt++) {
                            ht[Et++] = bt * C + yt
                        }
                        bt = N - wt - 1;
                        for (yt = C - wt - 1; yt > wt; yt--) {
                            ht[Et++] = bt * C + yt
                        }
                        yt = wt;
                        for (bt = N - wt - 1; bt > wt; bt--) {
                            ht[Et++] = bt * C + yt
                        }
                    }
                    ut = ht;
                    break;
                case "mosaicSpiralReverse":
                    var dt = N / 2,
                        yt, bt, wt, Et = J - 1;
                    for (wt = 0; wt < dt; wt++) {
                        bt = wt;
                        for (yt = wt; yt < C - wt - 1; yt++) {
                            ht[Et--] = bt * C + yt
                        }
                        yt = C - wt - 1;
                        for (bt = wt; bt < N - wt - 1; bt++) {
                            ht[Et--] = bt * C + yt
                        }
                        bt = N - wt - 1;
                        for (yt = C - wt - 1; yt > wt; yt--) {
                            ht[Et--] = bt * C + yt
                        }
                        yt = wt;
                        for (bt = N - wt - 1; bt > wt; bt--) {
                            ht[Et--] = bt * C + yt
                        }
                    }
                    ut = ht;
                    break;
                case "topLeftBottomRight":
                    for (var bt = 0; bt < N; bt++)
                        for (var yt = 0; yt < C; yt++) {
                            ht.push(yt + bt)
                        }
                    at = ht;
                    break;
                case "bottomRightTopLeft":
                    for (var bt = 0; bt < N; bt++)
                        for (var yt = 0; yt < C; yt++) {
                            ht.push(yt + bt)
                        }
                    at = ht.reverse();
                    break;
                case "bottomLeftTopRight":
                    for (var bt = N; bt > 0; bt--)
                        for (var yt = 0; yt < C; yt++) {
                            ht.push(yt + bt)
                        }
                    at = ht;
                    break;
                case "topRightBottomLeft":
                    for (var bt = 0; bt < N; bt++)
                        for (var yt = C; yt > 0; yt--) {
                            ht.push(yt + bt)
                        }
                    at = ht;
                    break
                }
                e.each(ut, function (n, s) {
                    function d() {
                        e(this).addClass("cameraeased");
                        if (e(".cameraeased", c).length >= 0) {
                            e(b).css({
                                visibility: "visible"
                            })
                        }
                        if (e(".cameraeased", c).length == J) {
                            vt();
                            e(".moveFromLeft, .moveFromRight, .moveFromTop, .moveFromBottom, .fadeIn, .fadeFromLeft, .fadeFromRight, .fadeFromTop, .fadeFromBottom", u).each(function () {
                                e(this).css("visibility", "hidden")
                            });
                            Z.eq(i).show().css("z-index", "999").removeClass("cameranext").addClass("cameracurrent");
                            Z.eq(r).css("z-index", "1").removeClass("cameracurrent");
                            e(".cameraContent", u).eq(i).addClass("cameracurrent");
                            if (r >= 0) {
                                e(".cameraContent", u).eq(r).removeClass("cameracurrent")
                            }
                            t.onEndTransition.call(this);
                            if (e("> div", l).eq(i).attr("data-video") != "hide" && e(".cameraContent.cameracurrent .imgFake", u).length) {
                                e(".cameraContent.cameracurrent .imgFake", u).click()
                            }
                            var n = Z.eq(i).find(".fadeIn").length;
                            var s = e(".cameraContent", u).eq(i).find(".moveFromLeft, .moveFromRight, .moveFromTop, .moveFromBottom, .fadeIn, .fadeFromLeft, .fadeFromRight, .fadeFromTop, .fadeFromBottom").length;
                            if (n != 0) {
                                e(".cameraSlide.cameracurrent .fadeIn", u).each(function () {
                                    if (e(this).attr("data-easing") != "") {
                                        var t = e(this).attr("data-easing")
                                    } else {
                                        var t = j
                                    }
                                    var r = e(this);
                                    if (typeof r.attr("data-outerWidth") === "undefined" || r.attr("data-outerWidth") === false || r.attr("data-outerWidth") === "") {
                                        var i = r.outerWidth();
                                        r.attr("data-outerWidth", i)
                                    } else {
                                        var i = r.attr("data-outerWidth")
                                    } if (typeof r.attr("data-outerHeight") === "undefined" || r.attr("data-outerHeight") === false || r.attr("data-outerHeight") === "") {
                                        var s = r.outerHeight();
                                        r.attr("data-outerHeight", s)
                                    } else {
                                        var s = r.attr("data-outerHeight")
                                    }
                                    var o = r.position();
                                    var u = o.left;
                                    var a = o.top;
                                    var f = r.attr("class");
                                    var l = r.index();
                                    var c = r.parents(".camerarelative").outerHeight();
                                    var h = r.parents(".camerarelative").outerWidth();
                                    if (f.indexOf("fadeIn") != -1) {
                                        r.animate({
                                            opacity: 0
                                        }, 0).css("visibility", "visible").delay(_ / n * .1 * (l - 1)).animate({
                                            opacity: 1
                                        }, _ / n * .15, t)
                                    } else {

                                        r.css("visibility", "visible")
                                    }
                                })
                            }
                            e(".cameraContent.cameracurrent", u).show();
                            if (s != 0) {
                                e(".cameraContent.cameracurrent .moveFromLeft, .cameraContent.cameracurrent .moveFromRight, .cameraContent.cameracurrent .moveFromTop, .cameraContent.cameracurrent .moveFromBottom, .cameraContent.cameracurrent .fadeIn, .cameraContent.cameracurrent .fadeFromLeft, .cameraContent.cameracurrent .fadeFromRight, .cameraContent.cameracurrent .fadeFromTop, .cameraContent.cameracurrent .fadeFromBottom", u).each(function () {
                                    if (e(this).attr("data-easing") != "") {
                                        var t = e(this).attr("data-easing")
                                    } else {
                                        var t = j
                                    }
                                    var n = e(this);
                                    var r = n.position();
                                    var i = r.left;
                                    var o = r.top;
                                    var u = n.attr("class");
                                    var a = n.index();
                                    var f = n.outerHeight();
                                    if (u.indexOf("moveFromLeft") != -1) {
                                        n.css({
                                            left: "-" + w + "px",
                                            right: "auto"
                                        });
                                        n.css("visibility", "visible").delay(_ / s * .1 * (a - 1)).animate({
                                            left: r.left
                                        }, _ / s * .15, t)
                                    } else if (u.indexOf("moveFromRight") != -1) {
                                        n.css({
                                            left: w + "px",
                                            right: "auto"
                                        });
                                        n.css("visibility", "visible").delay(_ / s * .1 * (a - 1)).animate({
                                            left: r.left
                                        }, _ / s * .15, t)
                                    } else if (u.indexOf("moveFromTop") != -1) {
                                        n.css({
                                            top: "-" + E + "px",
                                            bottom: "auto"
                                        });
                                        n.css("visibility", "visible").delay(_ / s * .1 * (a - 1)).animate({
                                            top: r.top
                                        }, _ / s * .15, t, function () {
                                            n.css({
                                                top: "auto",
                                                bottom: 0
                                            })
                                        })
                                    } else if (u.indexOf("moveFromBottom") != -1) {
                                        n.css({
                                            top: E + "px",
                                            bottom: "auto"
                                        });
                                        n.css("visibility", "visible").delay(_ / s * .1 * (a - 1)).animate({
                                            top: r.top
                                        }, _ / s * .15, t)
                                    } else if (u.indexOf("fadeFromLeft") != -1) {
                                        n.animate({
                                            opacity: 0
                                        }, 0).css({
                                            left: "-" + w + "px",
                                            right: "auto"
                                        });
                                        n.css("visibility", "visible").delay(_ / s * .1 * (a - 1)).animate({
                                            left: r.left,
                                            opacity: 1
                                        }, _ / s * .15, t)
                                    } else if (u.indexOf("fadeFromRight") != -1) {
                                        n.animate({
                                            opacity: 0
                                        }, 0).css({
                                            left: w + "px",
                                            right: "auto"
                                        });
                                        n.css("visibility", "visible").delay(_ / s * .1 * (a - 1)).animate({
                                            left: r.left,
                                            opacity: 1
                                        }, _ / s * .15, t)
                                    } else if (u.indexOf("fadeFromTop") != -1) {
                                        n.animate({
                                            opacity: 0
                                        }, 0).css({
                                            top: "-" + E + "px",
                                            bottom: "auto"
                                        });
                                        n.css("visibility", "visible").delay(_ / s * .1 * (a - 1)).animate({
                                            top: r.top,
                                            opacity: 1
                                        }, _ / s * .15, t, function () {
                                            n.css({
                                                top: "auto",
                                                bottom: 0
                                            })
                                        })
                                    } else if (u.indexOf("fadeFromBottom") != -1) {
                                        n.animate({
                                            opacity: 0
                                        }, 0).css({
                                            bottom: "-" + f + "px"
                                        });
                                        n.css("visibility", "visible").delay(_ / s * .1 * (a - 1)).animate({
                                            bottom: "0",
                                            opacity: 1
                                        }, _ / s * .15, t)
                                    } else if (u.indexOf("fadeIn") != -1) {
                                        n.animate({
                                            opacity: 0
                                        }, 0).css("visibility", "visible").delay(_ / s * .1 * (a - 1)).animate({
                                            opacity: 1
                                        }, _ / s * .15, t)
                                    } else {
                                        n.css("visibility", "visible")
                                    }
                                })
                            }
                            e(".cameraappended", c).remove();
                            l.removeClass("camerasliding");
                            Z.eq(r).hide();
                            var o = e(".camera_bar_cont", nt).width(),
                                h = e(".camera_bar_cont", nt).height(),
                                d;
                            if (a != "pie") {
                                d = .05
                            } else {
                                d = .005
                            }
                            e("#" + f).animate({
                                opacity: t.loaderOpacity
                            }, 200);
                            X = setInterval(function () {
                                if (l.hasClass("stopped")) {
                                    clearInterval(X)
                                }
                                if (a != "pie") {
                                    if (ft <= 1.002 && !l.hasClass("stopped") && !l.hasClass("paused") && !l.hasClass("hovered")) {
                                        ft = ft + d
                                    } else if (ft <= 1 && (l.hasClass("stopped") || l.hasClass("paused") || l.hasClass("stopped") || l.hasClass("hovered"))) {
                                        ft = ft
                                    } else {
                                        if (!l.hasClass("stopped") && !l.hasClass("paused") && !l.hasClass("hovered")) {
                                            clearInterval(X);
                                            rt();
                                            e("#" + f).animate({
                                                opacity: 0
                                            }, 200, function () {
                                                clearTimeout(V);
                                                V = setTimeout(mt, p);
                                                gt();
                                                t.onStartLoading.call(this)
                                            })
                                        }
                                    }
                                    switch (tt) {
                                    case "leftToRight":
                                        e("#" + f).animate({
                                            right: o - o * ft
                                        }, _ * d, "linear");
                                        break;
                                    case "rightToLeft":
                                        e("#" + f).animate({
                                            left: o - o * ft
                                        }, _ * d, "linear");
                                        break;
                                    case "topToBottom":
                                        e("#" + f).animate({
                                            bottom: h - h * ft
                                        }, _ * d, "linear");
                                        break;
                                    case "bottomToTop":
                                        e("#" + f).animate({
                                            bottom: h - h * ft
                                        }, _ * d, "linear");
                                        break
                                    }
                                } else {
                                    lt = ft;
                                    ct.clearRect(0, 0, t.pieDiameter, t.pieDiameter);
                                    ct.globalCompositeOperation = "destination-over";
                                    ct.beginPath();
                                    ct.arc(t.pieDiameter / 2, t.pieDiameter / 2, t.pieDiameter / 2 - t.loaderStroke, 0, Math.PI * 2, false);
                                    ct.lineWidth = t.loaderStroke;
                                    ct.strokeStyle = t.loaderBgColor;
                                    ct.stroke();
                                    ct.closePath();
                                    ct.globalCompositeOperation = "source-over";
                                    ct.beginPath();
                                    ct.arc(t.pieDiameter / 2, t.pieDiameter / 2, t.pieDiameter / 2 - t.loaderStroke, 0, Math.PI * 2 * lt, false);
                                    ct.lineWidth = t.loaderStroke - t.loaderPadding * 2;
                                    ct.strokeStyle = t.loaderColor;
                                    ct.stroke();
                                    ct.closePath();
                                    if (ft <= 1.002 && !l.hasClass("stopped") && !l.hasClass("paused") && !l.hasClass("hovered")) {
                                        ft = ft + d
                                    } else if (ft <= 1 && (l.hasClass("stopped") || l.hasClass("paused") || l.hasClass("hovered"))) {
                                        ft = ft
                                    } else {
                                        if (!l.hasClass("stopped") && !l.hasClass("paused") && !l.hasClass("hovered")) {
                                            clearInterval(X);
                                            rt();
                                            e("#" + f + ", .camera_canvas_wrap", nt).animate({
                                                opacity: 0
                                            }, 200, function () {
                                                clearTimeout(V);
                                                V = setTimeout(mt, p);
                                                gt();
                                                t.onStartLoading.call(this)
                                            })
                                        }
                                    }
                                }
                            }, _ * d)
                        }
                    }
                    if (s % C < K) {
                        G = 1
                    } else {
                        G = 0
                    } if (s % C == 0) {
                        st = 0
                    }
                    if (Math.floor(s / C) < Q) {
                        et = 1
                    } else {
                        et = 0
                    }
                    switch (H) {
                    case "simpleFade":
                        height = E;
                        width = w;
                        opacityOnGrid = 0;
                        break;
                    case "curtainTopLeft":
                        height = 0, width = Math.floor(w / C + G + 1), marginTop = "-" + Math.floor(E / N + et + 1) + "px";
                        break;
                    case "curtainTopRight":
                        height = 0, width = Math.floor(w / C + G + 1), marginTop = "-" + Math.floor(E / N + et + 1) + "px";
                        break;
                    case "curtainBottomLeft":
                        height = 0, width = Math.floor(w / C + G + 1), marginTop = Math.floor(E / N + et + 1) + "px";
                        break;
                    case "curtainBottomRight":
                        height = 0, width = Math.floor(w / C + G + 1), marginTop = Math.floor(E / N + et + 1) + "px";
                        break;
                    case "curtainSliceLeft":
                        height = 0, width = Math.floor(w / C + G + 1);
                        if (s % 2 == 0) {
                            marginTop = Math.floor(E / N + et + 1) + "px"
                        } else {
                            marginTop = "-" + Math.floor(E / N + et + 1) + "px"
                        }
                        break;
                    case "curtainSliceRight":
                        height = 0, width = Math.floor(w / C + G + 1);
                        if (s % 2 == 0) {
                            marginTop = Math.floor(E / N + et + 1) + "px"
                        } else {
                            marginTop = "-" + Math.floor(E / N + et + 1) + "px"
                        }
                        break;
                    case "blindCurtainTopLeft":
                        height = Math.floor(E / N + et + 1), width = 0, marginLeft = "-" + Math.floor(w / C + G + 1) + "px";
                        break;
                    case "blindCurtainTopRight":
                        height = Math.floor(E / N + et + 1), width = 0, marginLeft = Math.floor(w / C + G + 1) + "px";
                        break;
                    case "blindCurtainBottomLeft":
                        height = Math.floor(E / N + et + 1), width = 0, marginLeft = "-" + Math.floor(w / C + G + 1) + "px";
                        break;
                    case "blindCurtainBottomRight":
                        height = Math.floor(E / N + et + 1), width = 0, marginLeft = Math.floor(w / C + G + 1) + "px";
                        break;
                    case "blindCurtainSliceBottom":
                        height = Math.floor(E / N + et + 1), width = 0;
                        if (s % 2 == 0) {
                            marginLeft = "-" + Math.floor(w / C + G + 1) + "px"
                        } else {
                            marginLeft = Math.floor(w / C + G + 1) + "px"
                        }
                        break;
                    case "blindCurtainSliceTop":
                        height = Math.floor(E / N + et + 1), width = 0;
                        if (s % 2 == 0) {
                            marginLeft = "-" + Math.floor(w / C + G + 1) + "px"
                        } else {
                            marginLeft = Math.floor(w / C + G + 1) + "px"
                        }
                        break;
                    case "stampede":
                        height = 0;
                        width = 0;
                        marginLeft = w * .2 * (n % C - (C - Math.floor(C / 2))) + "px";
                        marginTop = E * .2 * (Math.floor(n / C) + 1 - (N - Math.floor(N / 2))) + "px";
                        break;
                    case "mosaic":
                        height = 0;
                        width = 0;
                        break;
                    case "mosaicReverse":
                        height = 0;
                        width = 0;
                        marginLeft = Math.floor(w / C + G + 1) + "px";
                        marginTop = Math.floor(E / N + et + 1) + "px";
                        break;
                    case "mosaicRandom":
                        height = 0;
                        width = 0;
                        marginLeft = Math.floor(w / C + G + 1) * .5 + "px";
                        marginTop = Math.floor(E / N + et + 1) * .5 + "px";
                        break;
                    case "mosaicSpiral":
                        height = 0;
                        width = 0;
                        marginLeft = Math.floor(w / C + G + 1) * .5 + "px";
                        marginTop = Math.floor(E / N + et + 1) * .5 + "px";
                        break;
                    case "mosaicSpiralReverse":
                        height = 0;
                        width = 0;
                        marginLeft = Math.floor(w / C + G + 1) * .5 + "px";
                        marginTop = Math.floor(E / N + et + 1) * .5 + "px";
                        break;
                    case "topLeftBottomRight":
                        height = 0;
                        width = 0;
                        break;
                    case "bottomRightTopLeft":
                        height = 0;
                        width = 0;
                        marginLeft = Math.floor(w / C + G + 1) + "px";
                        marginTop = Math.floor(E / N + et + 1) + "px";
                        break;
                    case "bottomLeftTopRight":
                        height = 0;
                        width = 0;
                        marginLeft = 0;
                        marginTop = Math.floor(E / N + et + 1) + "px";
                        break;
                    case "topRightBottomLeft":
                        height = 0;
                        width = 0;
                        marginLeft = Math.floor(w / C + G + 1) + "px";
                        marginTop = 0;
                        break;
                    case "scrollRight":
                        height = E;
                        width = w;
                        marginLeft = -w;
                        break;
                    case "scrollLeft":
                        height = E;
                        width = w;
                        marginLeft = w;
                        break;
                    case "scrollTop":
                        height = E;
                        width = w;
                        marginTop = E;
                        break;
                    case "scrollBottom":
                        height = E;
                        width = w;
                        marginTop = -E;
                        break;
                    case "scrollHorz":
                        height = E;
                        width = w;
                        if (r == 0 && i == D - 1) {
                            marginLeft = -w
                        } else if (r < i || r == D - 1 && i == 0) {
                            marginLeft = w
                        } else {
                            marginLeft = -w
                        }
                        break
                    }
                    var h = e(".cameraappended:eq(" + s + ")", c);
                    if (typeof X !== "undefined") {
                        clearInterval(X);
                        clearTimeout(V);
                        V = setTimeout(mt, P + L)
                    }
                    if (e(y).length) {
                        e(".camera_pag li", o).removeClass("cameracurrent");
                        e(".camera_pag li", o).eq(i).addClass("cameracurrent")
                    }
                    if (e(b).length) {
                        e("li", b).removeClass("cameracurrent");
                        e("li", b).eq(i).addClass("cameracurrent");
                        e("li", b).not(".cameracurrent").find("img").animate({
                            opacity: .5
                        }, 0);
                        e("li.cameracurrent img", b).animate({
                            opacity: 1
                        }, 0);
                        e("li", b).hover(function () {
                            e("img", this).stop(true, false).animate({
                                opacity: 1
                            }, 150)
                        }, function () {
                            if (!e(this).hasClass("cameracurrent")) {
                                e("img", this).stop(true, false).animate({
                                    opacity: .5
                                }, 150)
                            }
                        })
                    }
                    var p = parseFloat(P) + parseFloat(L);
                    if (H == "scrollLeft" || H == "scrollRight" || H == "scrollTop" || H == "scrollBottom" || H == "scrollHorz") {
                        t.onStartTransition.call(this);
                        p = 0;
                        h.delay((P + L) / J * at[n] * k * .5).css({
                            display: "block",
                            height: height,
                            "margin-left": marginLeft,
                            "margin-top": marginTop,
                            width: width
                        }).animate({
                            height: Math.floor(E / N + et + 1),
                            "margin-top": 0,
                            "margin-left": 0,
                            width: Math.floor(w / C + G + 1)
                        }, P - L, j, d);
                        Z.eq(r).delay((P + L) / J * at[n] * k * .5).animate({
                            "margin-left": marginLeft * -1,
                            "margin-top": marginTop * -1
                        }, P - L, j, function () {
                            e(this).css({
                                "margin-top": 0,
                                "margin-left": 0
                            })
                        })
                    } else {
                        t.onStartTransition.call(this);
                        p = parseFloat(P) + parseFloat(L);
                        if (q == "next") {
                            h.delay((P + L) / J * at[n] * k * .5).css({
                                display: "block",
                                height: height,
                                "margin-left": marginLeft,
                                "margin-top": marginTop,
                                width: width,
                                opacity: opacityOnGrid
                            }).animate({
                                height: Math.floor(E / N + et + 1),
                                "margin-top": 0,
                                "margin-left": 0,
                                opacity: 1,
                                width: Math.floor(w / C + G + 1)
                            }, P - L, j, d)
                        } else {
                            Z.eq(i).show().css("z-index", "999").addClass("cameracurrent");
                            Z.eq(r).css("z-index", "1").removeClass("cameracurrent");
                            e(".cameraContent", u).eq(i).addClass("cameracurrent");
                            e(".cameraContent", u).eq(r).removeClass("cameracurrent");
                            h.delay((P + L) / J * at[n] * k * .5).css({
                                display: "block",
                                height: Math.floor(E / N + et + 1),
                                "margin-top": 0,
                                "margin-left": 0,
                                opacity: 1,
                                width: Math.floor(w / C + G + 1)
                            }).animate({
                                height: height,
                                "margin-left": marginLeft,
                                "margin-top": marginTop,
                                width: width,
                                opacity: opacityOnGrid
                            }, P - L, j, d)
                        }
                    }
                })
            }
        }
        var r = {
            alignment: "center",
            autoAdvance: true,
            mobileAutoAdvance: true,
            barDirection: "leftToRight",
            barPosition: "bottom",
            cols: 6,
            easing: "easeInOutExpo",
            mobileEasing: "",
            fx: "random",
            mobileFx: "",
            gridDifference: 250,
            height: "50%",
            imagePath: "images/",
            hover: true,
            loader: "pie",
            loaderColor: "#eeeeee",
            loaderBgColor: "#222222",
            loaderOpacity: .8,
            loaderPadding: 2,
            loaderStroke: 7,
            minHeight: "200px",
            navigation: true,
            navigationHover: true,
            mobileNavHover: true,
            opacityOnGrid: false,
            overlayer: true,
            pagination: true,
            playPause: false,
            pauseOnClick: true,
            pieDiameter: 38,
            piePosition: "rightTop",
            portrait: false,
            rows: 4,
            slicedCols: 12,
            slicedRows: 8,
            slideOn: "random",
            thumbnails: false,
            thumbheight: "100",
            thumbwidth: "75",
            time: 7e3,
            transPeriod: 1500,
            fullpage: false,
            lightbox: "none",
            mobileimageresolution: 0,
            onEndTransition: function () {},
            onLoaded: function () {},
            onStartLoading: function () {},
            onStartTransition: function () {}
        };
        var t = e.extend({}, r, t);
        var o = e(this).addClass("camera_wrap");
        if (t.fullpage == true) {
            e(document.body).css("background", "none").prepend(o);
            o.css({
                height: "100%",
                "margin-left": 0,
                "margin-right": 0,
                "margin-top": 0,
                position: "fixed",
                visibility: "visible",
                left: 0,
                right: 0,
                "min-width": "100%",
                "min-height": "100%",
                width: "100%",
                "z-index": "-1"
            })
        }
        o.wrapInner('<div class="camera_src" />').wrapInner('<div class="camera_fakehover" />');
        var u = e(".camera_fakehover", o);
        u.append('<div class="camera_target"></div>');
        if (t.overlayer == true) {
            u.append('<div class="camera_overlayer"></div>')
        }
        u.append('<div class="camera_target_content"></div>');
        var a;
        if (navigator.userAgent.match(/MSIE 8.0/i) || navigator.userAgent.match(/MSIE 7.0/i) || navigator.userAgent.match(/MSIE 6.0/i)) {
            a = "bar"
        } else {
            a = t.loader
        } if (a == "pie") {
            u.append('<div class="camera_pie"></div>')
        } else if (a == "bar") {
            u.append('<div class="camera_bar"></div>')
        } else {
            u.append('<div class="camera_bar" style="display:none"></div>')
        } if (t.playPause == true) {
            u.append('<div class="camera_commands"></div>')
        }
        if (t.navigation == true) {
            u.append('<div class="camera_prev"><span></span></div>').append('<div class="camera_next"><span></span></div>')
        }
        if (t.thumbnails == true) {
            o.append('<div class="camera_thumbs_cont" />')
        }
        if (t.thumbnails == true && t.pagination != true) {
            e(".camera_thumbs_cont", o).wrap("<div />").wrap('<div class="camera_thumbs" />').wrap("<div />").wrap('<div class="camera_command_wrap" />')
        }
        if (t.pagination == true) {
            o.append('<div class="camera_pag"></div>')
        }
        o.append('<div class="camera_loader"></div>');
        e(".camera_caption", o).each(function () {
            e(this).wrapInner("<div />")
        });
        var f = "pie_" + o.attr("id"),
            l = e(".camera_src", o),
            c = e(".camera_target", o),
            h = e(".camera_target_content", o),
            p = e(".camera_pie", o),
            d = e(".camera_bar", o),
            v = e(".camera_prev", o),
            m = e(".camera_next", o),
            g = e(".camera_commands", o),
            y = e(".camera_pag", o),
            b = e(".camera_thumbs_cont", o);
        var w, E;
        var S = parseInt(e(document.body).width());
        imgresolution = 0;
        if (t.mobileimageresolution) {
            var x = t.mobileimageresolution.split(",");
            for (i = 0; i < x.length; i++) {
                if (S <= x[i] && (imgresolution != 0 && x[i] <= imgresolution || imgresolution == 0 && S < Math.max.apply(Math, x))) {
                    imgresolution = x[i]
                }
            }
        }
        var T = new Array;
        var N;
        e("> div", l).each(function () {
            N = e(this).attr("data-src");
            if (imgresolution) {
                imgsrctmp = N.split("/");
                imgnametmp = imgsrctmp[imgsrctmp.length - 1];
                imgsrctmp[imgsrctmp.length - 1] = imgresolution + "/" + imgnametmp;
                N = imgsrctmp.join("/")
            }
            T.push(N)
        });
        var C = new Array;
        var k = new Array;
        var L = new Array;
        e("> div", l).each(function () {
            if (e(this).attr("data-link")) {
                C.push(e(this).attr("data-link"))
            } else {
                C.push("")
            } if (e(this).attr("data-rel")) {
                k.push('rel="' + e(this).attr("data-rel") + '" ')
            } else {
                k.push("")
            } if (e(this).attr("data-title")) {
                L.push('title="' + e(this).attr("data-title") + '" ')
            } else {
                L.push("")
            }
        });
        var A = new Array;
        e("> div", l).each(function () {
            if (e(this).attr("data-target")) {
                A.push(e(this).attr("data-target"))
            } else {
                A.push("")
            }
        });
        var O = new Array;
        e("> div", l).each(function () {
            if (e(this).attr("data-portrait")) {
                O.push(e(this).attr("data-portrait"))
            } else {
                O.push("")
            }
        });
        var M = new Array;
        e("> div", l).each(function () {
            if (e(this).attr("data-alignment")) {
                M.push(e(this).attr("data-alignment"))
            } else {
                M.push("")
            }
        });
        var _ = new Array;
        e("> div", l).each(function () {
            if (e(this).attr("data-thumb")) {
                _.push(e(this).attr("data-thumb"))
            } else {
                _.push("")
            }
        });
        var D = T.length;
        e(h).append('<div class="cameraContents" />');
        var P;
        for (P = 0; P < D; P++) {
            e(".cameraContents", h).append('<div class="cameraContent" />');
            if (C[P] != "") {
                var H = e("> div ", l).eq(P).attr("data-box");
                if (typeof H !== "undefined" && H !== false && H != "") {
                    H = 'data-box="' + e("> div ", l).eq(P).attr("data-box") + '"'
                } else {
                    H = ""
                }
                e(".camera_target_content .cameraContent:eq(" + P + ")", o).append('<a class="camera_link" ' + k[P] + L[P] + 'href="' + C[P] + '" ' + H + ' target="' + A[P] + '"></a>')
            }
        }
        e(".camera_caption", o).each(function () {
            var t = e(this).parent().index(),
                n = o.find(".cameraContent").eq(t);
            e(this).appendTo(n)
        });
        c.append('<div class="cameraCont" />');
        var B = e(".cameraCont", o);
        var j;
        for (j = 0; j < D; j++) {
            B.append('<div class="cameraSlide cameraSlide_' + j + '" />');
            var F = e("> div:eq(" + j + ")", l);
            c.find(".cameraSlide_" + j).clone(F)
        }
        e(window).bind("load resize pageshow", function () {
            vt();
            I()
        });
        B.append('<div class="cameraSlide cameraSlide_' + j + '" />');
        var q;
        o.show();
        var w = c.width();
        var E = c.height();
        var R;
        e(window).bind("resize pageshow", function () {
            if (q == true) {
                W()
            }
            e("ul", b).animate({
                "margin-top": 0
            }, 0, vt);
            if (!l.hasClass("paused")) {
                l.addClass("paused");
                if (e(".camera_stop", nt).length) {
                    e(".camera_stop", nt).hide();
                    e(".camera_play", nt).show();
                    if (a != "none") {
                        e("#" + f).hide()
                    }
                } else {
                    if (a != "none") {
                        e("#" + f).hide()
                    }
                }
                clearTimeout(R);
                R = setTimeout(function () {
                    l.removeClass("paused");
                    if (e(".camera_play", nt).length) {
                        e(".camera_play", nt).hide();
                        e(".camera_stop", nt).show();
                        if (a != "none") {
                            e("#" + f).fadeIn()
                        }
                    } else {
                        if (a != "none") {
                            e("#" + f).fadeIn()
                        }
                    }
                }, 1500)
            }
        });
        z();
        var X, V;
        var J, K, Q, g, y;
        var G, Y;
        if (s() && t.mobileAutoAdvance != "") {
            K = t.mobileAutoAdvance
        } else {
            K = t.autoAdvance
        } if (K == false) {
            l.addClass("paused")
        }
        if (s() && t.mobileNavHover != "") {
            Q = t.mobileNavHover
        } else {
            Q = t.navigationHover
        } if (l.length != 0) {
            var Z = e(".cameraSlide", c);
            Z.wrapInner('<div class="camerarelative" />');
            var et;
            var tt = t.barDirection;
            var nt = o;
            e("iframe", u).each(function () {
                var t = e(this);
                var n = t.attr("src");
                t.attr("data-src", n);
                var r = t.parent().index("#" + o.attr("id") + " .camera_src > div");
                e(".camera_target_content .cameraContent:eq(" + r + ")", o).append(t)
            });

            function rt() {
                e("iframe", u).each(function () {
                    e(".camera_caption", u).show();
                    var n = e(this);
                    var r = n.attr("data-src");
                    n.attr("src", r);
                    var i = t.imagePath + "blank.gif";
                    var s = new Image;
                    s.src = i;
                    if (t.height.indexOf("%") != -1) {
                        var a = Math.round(w / (100 / parseFloat(t.height)));
                        if (t.minHeight != "" && a < parseFloat(t.minHeight)) {
                            E = parseFloat(t.minHeight)
                        } else {
                            E = a
                        }
                    } else if (t.height == "auto") {
                        E = o.height()
                    } else {
                        E = parseFloat(t.height)
                    }
                    n.after(e(s).attr({
                        "class": "imgFake",
                        width: w,
                        height: E
                    }));
                    var f = n.clone();
                    n.remove();
                    e(s).bind("click", function () {
                        if (e(this).css("position") == "absolute") {
                            e(this).remove();
                            if (r.indexOf("vimeo") != -1 || r.indexOf("youtube") != -1) {
                                if (r.indexOf("?") != -1) {
                                    autoplay = "&autoplay=1"
                                } else {
                                    autoplay = "?autoplay=1"
                                }
                            } else if (r.indexOf("dailymotion") != -1) {
                                if (r.indexOf("?") != -1) {
                                    autoplay = "&autoPlay=1"
                                } else {
                                    autoplay = "?autoPlay=1"
                                }
                            }
                            f.attr("src", r + autoplay);
                            Y = true
                        } else {
                            e(this).css({
                                position: "absolute",
                                top: 0,
                                left: 0,
                                zIndex: 10
                            }).after(f);
                            f.css({
                                position: "absolute",
                                top: 0,
                                left: 0,
                                zIndex: 9
                            })
                        }
                    })
                })
            }
            rt();
            if (t.hover == true) {
                if (!s()) {
                    u.hover(function () {
                        l.addClass("hovered")
                    }, function () {
                        l.removeClass("hovered")
                    })
                }
            }
            if (Q == true) {
                e(v, o).animate({
                    opacity: 0
                }, 0);
                e(m, o).animate({
                    opacity: 0
                }, 0);
                e(g, o).animate({
                    opacity: 0
                }, 0);
                if (s()) {
                    u.on("vmouseover", function () {
                        e(v, o).animate({
                            opacity: 1
                        }, 200);
                        e(m, o).animate({
                            opacity: 1
                        }, 200);
                        e(g, o).animate({
                            opacity: 1
                        }, 200)
                    });
                    u.on("vmouseout", function () {
                        e(v, o).delay(500).animate({
                            opacity: 0
                        }, 200);
                        e(m, o).delay(500).animate({
                            opacity: 0
                        }, 200);
                        e(g, o).delay(500).animate({
                            opacity: 0
                        }, 200)
                    })
                } else {
                    u.hover(function () {
                        e(v, o).animate({
                            opacity: 1
                        }, 200);
                        e(m, o).animate({
                            opacity: 1
                        }, 200);
                        e(g, o).animate({
                            opacity: 1
                        }, 200)
                    }, function () {
                        e(v, o).animate({
                            opacity: 0
                        }, 200);
                        e(m, o).animate({
                            opacity: 0
                        }, 200);
                        e(g, o).animate({
                            opacity: 0
                        }, 200)
                    })
                }
            }
            nt.on("click", ".camera_stop", function () {
                K = false;
                l.addClass("paused");
                if (e(".camera_stop", nt).length) {
                    e(".camera_stop", nt).hide();
                    e(".camera_play", nt).show();
                    if (a != "none") {
                        e("#" + f).hide()
                    }
                } else {
                    if (a != "none") {
                        e("#" + f).hide()
                    }
                }
            });
            nt.on("click", ".camera_play", function () {
                K = true;
                l.removeClass("paused");
                if (e(".camera_play", nt).length) {
                    e(".camera_play", nt).hide();
                    e(".camera_stop", nt).show();
                    if (a != "none") {
                        e("#" + f).show()
                    }
                } else {
                    if (a != "none") {
                        e("#" + f).show()
                    }
                }
            });
            if (t.pauseOnClick == true) {
                e(".camera_target_content", u).mouseup(function () {
                    K = false;
                    l.addClass("paused");
                    e(".camera_stop", nt).hide();
                    e(".camera_play", nt).show();
                    e("#" + f).hide()
                })
            }
            e(".cameraContent, .imgFake", u).hover(function () {
                G = true
            }, function () {
                G = false
            });
            e(".cameraContent, .imgFake", u).bind("click", function () {
                if (Y == true && G == true) {
                    K = false;
                    e(".camera_caption", u).hide();
                    l.addClass("paused");
                    e(".camera_stop", nt).hide();
                    e(".camera_play", nt).show();
                    e("#" + f).hide()
                }
            })
        }
        if (a != "pie") {
            d.append('<span class="camera_bar_cont" />');
            e(".camera_bar_cont", d).animate({
                opacity: t.loaderOpacity
            }, 0).css({
                position: "absolute",
                left: 0,
                right: 0,
                top: 0,
                bottom: 0,
                "background-color": t.loaderBgColor
            }).append('<span id="' + f + '" />');
            e("#" + f).animate({
                opacity: 0
            }, 0);
            var ot = e("#" + f);
            ot.css({
                position: "absolute",
                "background-color": t.loaderColor
            });
            switch (t.barPosition) {
            case "left":
                d.css({
                    right: "auto",
                    width: t.loaderStroke
                });
                break;
            case "right":
                d.css({
                    left: "auto",
                    width: t.loaderStroke
                });
                break;
            case "top":
                d.css({
                    bottom: "auto",
                    height: t.loaderStroke
                });
                break;
            case "bottom":
                d.css({
                    top: "auto",
                    height: t.loaderStroke
                });
                break
            }
            switch (tt) {
            case "leftToRight":
                ot.css({
                    left: 0,
                    right: 0,
                    top: t.loaderPadding,
                    bottom: t.loaderPadding
                });
                break;
            case "rightToLeft":
                ot.css({
                    left: 0,
                    right: 0,
                    top: t.loaderPadding,
                    bottom: t.loaderPadding
                });
                break;
            case "topToBottom":
                ot.css({
                    left: t.loaderPadding,
                    right: t.loaderPadding,
                    top: 0,
                    bottom: 0
                });
                break;
            case "bottomToTop":
                ot.css({
                    left: t.loaderPadding,
                    right: t.loaderPadding,
                    top: 0,
                    bottom: 0
                });
                break
            }
        } else {
            p.append('<canvas id="' + f + '"></canvas>');
            var ut;
            var ot = document.getElementById(f);
            ot.setAttribute("width", t.pieDiameter);
            ot.setAttribute("height", t.pieDiameter);
            var at;
            switch (t.piePosition) {
            case "leftTop":
                at = "left:0; top:0;";
                break;
            case "rightTop":
                at = "right:0; top:0;";
                break;
            case "leftBottom":
                at = "left:0; bottom:0;";
                break;
            case "rightBottom":
                at = "right:0; bottom:0;";
                break
            }
            ot.setAttribute("style", "position:absolute; z-index:1002; " + at);
            var ft;
            var lt;
            if (ot && ot.getContext) {
                var ct = ot.getContext("2d");
                ct.rotate(Math.PI * (3 / 2));
                ct.translate(-t.pieDiameter, 0)
            }
        } if (a == "none" || K == false) {
            e("#" + f).hide();
            e(".camera_canvas_wrap", nt).hide()
        }
        if (e(y).length) {
            e(y).append('<ul class="camera_pag_ul" />');
            var ht;
            for (ht = 0; ht < D; ht++) {
                e(".camera_pag_ul", o).append('<li class="pag_nav_' + ht + '" style="position:relative; z-index:1002"><span><span>' + ht + "</span></span></li>")
            }
            e(".camera_pag_ul li", o).hover(function () {
                e(this).addClass("camera_hover");
                if (e(".camera_thumb", this).length) {
                    var t = e(".camera_thumb", this).outerWidth(),
                        n = e(".camera_thumb", this).outerHeight(),
                        r = e(this).outerWidth();
                    e(".camera_thumb", this).show().css({
                        top: "-" + n + "px",
                        left: "-" + (t - r) / 2 + "px"
                    }).animate({
                        opacity: 1,
                        "margin-top": "-3px"
                    }, 200);
                    e(".thumb_arrow", this).show().animate({
                        opacity: 1,
                        "margin-top": "-3px"
                    }, 200)
                }
            }, function () {
                e(this).removeClass("camera_hover");
                e(".camera_thumb", this).animate({
                    "margin-top": "-20px",
                    opacity: 0
                }, 200, function () {
                    e(this).css({
                        marginTop: "5px"
                    }).hide()
                });
                e(".thumb_arrow", this).animate({
                    "margin-top": "-20px",
                    opacity: 0
                }, 200, function () {
                    e(this).css({
                        marginTop: "5px"
                    }).hide()
                })
            })
        }
        t.onStartLoading.call(this);
        gt();
        pt();
        var dt = true;
        if (e(g).length) {
            e(g).append('<div class="camera_play"></div>').append('<div class="camera_stop"></div>');
            if (K == true) {
                e(".camera_play", nt).hide();
                e(".camera_stop", nt).show()
            } else {
                e(".camera_stop", nt).hide();
                e(".camera_play", nt).show()
            }
        }
        mt();
        e(".moveFromLeft, .moveFromRight, .moveFromTop, .moveFromBottom, .fadeIn, .fadeFromLeft, .fadeFromRight, .fadeFromTop, .fadeFromBottom", u).each(function () {
            e(this).css("visibility", "hidden")
        });
        U();
        if (e(v).length) {
            e(v).click(function () {
                if (!l.hasClass("camerasliding")) {
                    var n = parseFloat(e(".cameraSlide.cameracurrent", c).index());
                    clearInterval(X);
                    rt();
                    e("#" + f + ", .camera_canvas_wrap", o).animate({
                        opacity: 0
                    }, 0);
                    mt();
                    if (n != 0) {
                        gt(n)
                    } else {
                        gt(D)
                    }
                    t.onStartLoading.call(this)
                }
            })
        }
        if (e(m).length) {
            e(m).click(function () {
                if (!l.hasClass("camerasliding")) {
                    var n = parseFloat(e(".cameraSlide.cameracurrent", c).index());
                    clearInterval(X);
                    rt();
                    e("#" + f + ", .camera_canvas_wrap", nt).animate({
                        opacity: 0
                    }, 0);
                    mt();
                    if (n == D - 1) {
                        gt(1)
                    } else {
                        gt(n + 2)
                    }
                    t.onStartLoading.call(this)
                }
            })
        }
        if (s()) {
            u.bind("swipeleft", function (n) {
                if (!l.hasClass("camerasliding")) {
                    var r = parseFloat(e(".cameraSlide.cameracurrent", c).index());
                    clearInterval(X);
                    rt();
                    e("#" + f + ", .camera_canvas_wrap", nt).animate({
                        opacity: 0
                    }, 0);
                    mt();
                    if (r == D - 1) {
                        gt(1)
                    } else {
                        gt(r + 2)
                    }
                    t.onStartLoading.call(this)
                }
            });
            u.bind("swiperight", function (n) {
                if (!l.hasClass("camerasliding")) {
                    var r = parseFloat(e(".cameraSlide.cameracurrent", c).index());
                    clearInterval(X);
                    rt();
                    e("#" + f + ", .camera_canvas_wrap", nt).animate({
                        opacity: 0
                    }, 0);
                    mt();
                    if (r != 0) {
                        gt(r)
                    } else {
                        gt(D)
                    }
                    t.onStartLoading.call(this)
                }
            })
        }
        if (e(y).length) {
            e(".camera_pag li", o).click(function () {
                if (!l.hasClass("camerasliding")) {
                    var n = parseFloat(e(this).index());
                    var r = parseFloat(e(".cameraSlide.cameracurrent", c).index());
                    if (n != r) {
                        clearInterval(X);
                        rt();
                        e("#" + f + ", .camera_canvas_wrap", nt).animate({
                            opacity: 0
                        }, 0);
                        mt();
                        gt(n + 1);
                        t.onStartLoading.call(this)
                    }
                }
            })
        }
        if (e(b).length) {
            e(".pix_thumb img", b).click(function () {
                if (!l.hasClass("camerasliding")) {
                    var n = parseFloat(e(this).parents("li").index());
                    var r = parseFloat(e(".cameracurrent", c).index());
                    if (n != r) {
                        clearInterval(X);
                        rt();
                        e("#" + f + ", .camera_canvas_wrap", nt).animate({
                            opacity: 0
                        }, 0);
                        e(".pix_thumb", b).removeClass("cameracurrent");
                        e(this).parents("li").addClass("cameracurrent");
                        mt();
                        gt(n + 1);
                        vt();
                        t.onStartLoading.call(this)
                    }
                }
            });
            e(".camera_thumbs_cont .camera_prevThumbs", nt).hover(function () {
                e(this).stop(true, false).animate({
                    opacity: 1
                }, 250)
            }, function () {
                e(this).stop(true, false).animate({
                    opacity: .7
                }, 250)
            });
            e(".camera_prevThumbs", nt).click(function () {
                var t = 0,
                    n = e(b).outerWidth(),
                    r = e("ul", b).offset().left,
                    i = e("> div", b).offset().left,
                    s = i - r;
                e(".camera_visThumb", b).each(function () {
                    var n = e(this).outerWidth();
                    t = t + n
                });
                if (s - t > 0) {
                    e("ul", b).animate({
                        "margin-left": "-" + (s - t) + "px"
                    }, 500, I)
                } else {
                    e("ul", b).animate({
                        "margin-left": 0
                    }, 500, I)
                }
            });
            e(".camera_thumbs_cont .camera_nextThumbs", nt).hover(function () {
                e(this).stop(true, false).animate({
                    opacity: 1
                }, 250)
            }, function () {
                e(this).stop(true, false).animate({
                    opacity: .7
                }, 250)
            });
            e(".camera_nextThumbs", nt).click(function () {
                var t = 0,
                    n = e(b).outerWidth(),
                    r = e("ul", b).outerWidth(),
                    i = e("ul", b).offset().left,
                    s = e("> div", b).offset().left,
                    o = s - i;
                e(".camera_visThumb", b).each(function () {
                    var n = e(this).outerWidth();
                    t = t + n
                });
                if (o + t + t < r) {
                    e("ul", b).animate({
                        "margin-left": "-" + (o + t) + "px"
                    }, 500, I)
                } else {
                    e("ul", b).animate({
                        "margin-left": "-" + (r - n) + "px"
                    }, 500, I)
                }
            })
        }
    }
})(jQuery);
(function (e) {
    e.fn.cameraStop = function () {
        var t = e(this),
            n = e(".camera_src", t),
            r = "pie_" + t.index();
        n.addClass("stopped");
        if (e(".camera_showcommands").length) {
            var i = e(".camera_thumbs_wrap", t)
        } else {
            var i = t
        }
    }
})(jQuery);
(function (e) {
    e.fn.cameraPause = function () {
        var t = e(this);
        var n = e(".camera_src", t);
        n.addClass("paused")
    }
})(jQuery);
(function (e) {
    e.fn.cameraResume = function () {
        var t = e(this);
        var n = e(".camera_src", t);
        if (typeof autoAdv === "undefined" || autoAdv !== true) {
            n.removeClass("paused")
        }
    }
})(jQuery);