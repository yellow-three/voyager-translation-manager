@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '. "Çeviri Yöneticisi" )

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-bread"></i> Çeviri Yöneticisi
        </h1>
        @if(isset($group))
            <form class="form-inline form-publish" style="display:inline" method="POST"
                  action="{{ action('\App\Http\Controllers\TranslationManagerController@postPublish', $group) }}"
                  data-remote="true" role="form"
                  data-confirm="Are you sure you want to publish the translations group {{ $group }}? This will overwrite existing language files.">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-info"
                        data-disable-with="Publishing..">Çevirileri Yayınlayın</button>
                <a href="{{ action('\App\Http\Controllers\TranslationManagerController@getIndex') }}"
                   class="btn btn-default">Geri</a>
            </form>
        @endif
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <p>Uyarı, <code>php artisan translate:export</code> komutu veya yayınla düğmesi kullanılarak uygulama/lang dosyasına geri aktarılana kadar çeviriler görünmez.</p>
        <div class="alert alert-success success-import" style="display:none;">
            <p>İçe aktarma tamamlandı, <strong class="counter">N</strong> öğe işlendi! Grupları yenilemek için bu
                sayfayı yeniden yükleyin!</p>
        </div>
        <div class="alert alert-success success-find" style="display:none;">
            <p>Çeviri araması bitti, <strong class="counter">N</strong> öğe bulundu!</p>
        </div>
        <div class="alert alert-success success-publish" style="display:none;">
            <p>'{{ $group }}' grubunun çevirilerinin yayınlanması tamamlandı!</p>
        </div>
        <div class="alert alert-success success-publish-all" style="display:none;">
            <p>Tüm grup için çevirilerin yayınlanması tamamlandı!</p>
        </div>
        @if(Session::has('successPublish'))
            <div class="alert alert-info">
                {{ Session::get('successPublish') }}
            </div>
        @endif


        @if(!isset($group))
            <form class="form-import" method="POST"
                  action="{{ action('\App\Http\Controllers\TranslationManagerController@postImport') }}"
                  data-remote="true" role="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <select name="replace" class="form-control">
                                <option value="0">Yeni Çeviriler Ekle</option>
                                <option value="1">Mevcut Çevirileri Değiştir</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-success btn-block"
                                    data-disable-with="Yükleniyor..">Grupları İçe Aktar</button>
                        </div>
                    </div>
                </div>
            </form>
            <form class="form-find" method="POST"
                  action="{{ action('\App\Http\Controllers\TranslationManagerController@postFind') }}"
                  data-remote="true" role="form"
                  data-confirm="Uygulama klasörünüzü taramak istediğinizden emin misiniz? Bulunan tüm çeviri anahtarları veritabanına eklenecektir.">
                <div class="form-group">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-info" data-disable-with="Aranıyor..">
                        Dosyalardaki çevirileri bulun
                    </button>
                </div>
            </form>
        @endif
        @if(isset($group))
            <form class="form-inline form-publish" method="POST"
                  action="{{ action('\App\Http\Controllers\TranslationManagerController@postPublish', $group) }}"
                  data-remote="true" role="form"
                  data-confirm="{{ $group }} çeviri grubunu yayınlamak istediğinizden emin misiniz? Bu, mevcut dil dosyalarının üzerine yazacaktır.">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-info"
                        data-disable-with="Yayınlanıyor..">Çevirileri Yayınlayın</button>
                <a href="{{ action('\App\Http\Controllers\TranslationManagerController@getIndex') }}"
                   class="btn btn-default">Geri</a>
            </form>
        @endif

        <form role="form" method="POST"
              action="{{ action('\App\Http\Controllers\TranslationManagerController@postAddGroup') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <p>Grup çevirilerini görüntülemek için bir grup seçin. Hiçbir grup görünmüyorsa, geçişleri
                    çalıştırdığınızdan ve çevirileri içe aktardığınızdan emin olun.</p>
                <select name="group" id="group" class="form-control group-select">
                    @foreach($groups as $key => $value)
                        <option value="{{ $key }}" {{ $key == $group ? 'selected':'' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Yeni bir grup adı girin ve o grupta çevirileri düzenlemeye başlayın</label>
                <input type="text" class="form-control" name="new-group"/>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default" name="add-group" value="Grup ekleyin ve düzenleyin"/>
            </div>
        </form>
        @if($group)
            <form action="{{ action('\App\Http\Controllers\TranslationManagerController@postAdd', array($group)) }}"
                  method="POST" role="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label>Bu gruba yeni anahtar ekle</label>
                    <textarea class="form-control" rows="3" name="keys"
                              placeholder="Grup öneki olmadan satır başına 1 anahtar ekleyin"></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" value="Anahtar ekle" class="btn btn-primary">
                </div>
            </form>
            @if(false)
                <div class="row">
                    <div class="col-sm-2">
                        <span class="btn btn-default enable-auto-translate-group">Otomatik Çeviriyi Kullan</span>
                    </div>
                </div>
                <form class="form-add-locale autotranslate-block-group hidden" method="POST" role="form"
                      action="{{ action('\App\Http\Controllers\TranslationManagerController@postTranslateMissing') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="base-locale">Otomatik Çeviriler için Temel Yerel Ayar</label>
                                <select name="base-locale" id="base-locale" class="form-control">
                                    @foreach ($locales as $locale)
                                        <option value="{{ $locale }}">{{ $locale }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="new-locale">Hedef yerel ayar anahtarını girin</label>
                                <input type="text" name="new-locale" class="form-control" id="new-locale"
                                       placeholder="Hedef yerel ayar anahtarını girin"/>
                            </div>
                            @if(!config('laravel_google_translate.google_translate_api_key'))
                                <p>
                                    <code>
                                        Google Translate API'sini kullanmak istiyorsanız,
                                        tanmuhittin/laravel-google-translate yükleyin ve laravel_google_translate
                                        yapılandırma dosyasına Google Translate API anahtarınızı girin
                                    </code>
                                </p>
                                <div class="form-group">
                                    <input type="hidden" name="with-translations" value="1">
                                    <input type="hidden" name="file" value="{{ $group }}">
                                    <button type="submit" class="btn btn-default btn-block"
                                            data-disable-with="Ekleniyor..">
                                        Eksik çevirileri otomatik çevir
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            @endif
            <hr>
            <h4>Toplam: {{ $numTranslations }}, değiştirilmiş: {{ $numChanged }}</h4>
            <style>
                a.status-1 {
                    font-weight: bold !important;
                }
            </style>
            <table id="dataTable" class="table table-hover">
                <thead>
                <tr>
                    <th width="15%">Key</th>
                    @foreach ($locales as $locale)
                        <th>{{ $locale }}</th>
                    @endforeach
                    @if ($deleteEnabled)
                        <th>&nbsp;</th>
                    @endif
                </tr>
                </thead>
                <tbody>

                @foreach ($translations as $key => $translation)
                    <tr id="{{ $key }}">
                        <td>{{ $key }}</td>
                        @foreach ($locales as $locale)
                                <?php $t = isset($translation[$locale]) ? $translation[$locale] : null ?>

                            <td>
                                <a href="#edit" id="locale-{{ $locale }}"
                                   onclick="editableFunction(this,this.dataset)"
                                   class="editable status-{{ $t ? $t->status : 0 }} locale-{{ $locale }}"
                                   data-locale="{{ $locale }}"
                                   data-name="{{ $locale . "|" . $key }}"
                                   data-value="{{ $t ? $t->value : '' }}"
                                   data-type="textarea"
                                   data-pk="{{ $t ? $t->id : 0 }}"
                                   data-url="{{ $editUrl }}"
                                   data-title="Çeviriyi girin">{{ ($t && $t->value) ? $t->value : 'Boş' }}</a>
                            </td>
                        @endforeach
                        @if ($deleteEnabled)
                            <td>
                                <a href="{{ action('\App\Http\Controllers\TranslationManagerController@postDelete', [$group, $key]) }}"
                                   class="delete-key"
                                   data-confirm="'{{ $key }}' çevirilerini silmek istediğinizden emin misiniz?"><span
                                        class="glyphicon glyphicon-trash"></span></a>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <fieldset>
                <legend>Desteklenen yerel ayarlar</legend>
                <p>
                    Mevcut desteklenen yerel ayarlar:
                </p>
                <form class="form-remove-locale" method="POST" role="form"
                      action="{{ action('\App\Http\Controllers\TranslationManagerController@postRemoveLocale') }}"
                      data-confirm="Bu yerel ayarı ve tüm verileri kaldıracağınızdan emin misiniz?">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <ul class="list-locales">
                        @foreach($locales as $locale)
                            <li>
                                <div class="form-group">
                                    <button type="submit" name="remove-locale[{{ $locale }}]"
                                            class="btn btn-danger btn-xs" data-disable-with="...">
                                        &times;
                                    </button>
                                    {{ $locale }}

                                </div>
                            </li>
                        @endforeach
                    </ul>
                </form>
                @if(false)
                    <form class="form-add-locale" method="POST" role="form"
                          action="{{ action('\App\Http\Controllers\TranslationManagerController@postAddLocale') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <p>
                                Yeni yerel ayar anahtarını girin:
                            </p>
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="text" name="new-locale" class="form-control"/>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-default btn-block"
                                            data-disable-with="Ekleniyor..">
                                        Yeni yerel ayar ekle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </fieldset>
            <fieldset>
                <legend>Tüm çevirileri dışa aktar</legend>
                <form class="form-inline form-publish-all" method="POST"
                      action="{{ action('\App\Http\Controllers\TranslationManagerController@postPublish', '*') }}"
                      data-remote="true" role="form"
                      data-confirm="Tüm çeviriler grubunu yayınlamak istediğinizden emin misiniz? Bu, mevcut dil dosyalarının üzerine yazacaktır.">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-primary" data-disable-with="Yayınlanıyor..">Tümünü yayınla
                    </button>
                </form>
            </fieldset>

        @endif
    </div>

@stop

@section('css')
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
          rel="stylesheet"/>
@stop

@section('javascript')
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            @if($group)
            var table = $('#dataTable').DataTable({!! json_encode(
                    array_merge([
                        "order" => [[0,"asc"]],
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [
                            ['targets' => 'dt-not-orderable', 'searchable' =>  false, 'orderable' => false],
                        ],
                    ],
                    config('voyager.dashboard.data_tables', []))
                , true) !!});
            @endif
        });

        function editableFunction(el, data) {

            var thisValue;
            Swal.fire({
                title: data.title,
                input: data.type,
                inputValue: data.value,
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Kaydet',
                cancelButtonText: 'Kapat',
                showLoaderOnConfirm: true,
                preConfirm: (value) => {

                    if (value == '')
                        return Swal.showValidationMessage(
                            'Çeviri Zorunludur'
                        );
                    const requestOptions = {
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json, text-plain, */*",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": '{{ csrf_token() }}',
                        },
                        method: 'POST',
                        credentials: "same-origin",
                        body: JSON.stringify({"name": data.name, "value": value})
                    }

                    return fetch(data.url, requestOptions)
                        .then(response => {
                            //console.log('response', response);
                            //console.log('response_json', response.json());
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            //$(el).html(value);
                            thisValue = value;
                            //$(el).dataset.value=value;
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(result.value);
                    if (result.value.status) {
                        $(el).text(thisValue);
                        $(el).attr('data-value', thisValue);
                        $(el).removeClass('status-0').addClass('status-1');
                    }
                    /*Swal.fire({
                        title: result.value.status,
                    })*/
                }
            })
        }
    </script>
    <script>
        //https://github.com/rails/jquery-ujs/blob/master/src/rails.js
        (function (e, t) {
            if (e.rails !== t) {
                e.error("jquery-ujs has already been loaded!")
            }
            var n;
            var r = e(document);
            e.rails = n = {
                linkClickSelector: "a[data-confirm], a[data-method], a[data-remote], a[data-disable-with]",
                buttonClickSelector: "button[data-remote], button[data-confirm]",
                inputChangeSelector: "select[data-remote], input[data-remote], textarea[data-remote]",
                formSubmitSelector: "form",
                formInputClickSelector: "form input[type=submit], form input[type=image], form button[type=submit], form button:not([type])",
                disableSelector: "input[data-disable-with], button[data-disable-with], textarea[data-disable-with]",
                enableSelector: "input[data-disable-with]:disabled, button[data-disable-with]:disabled, textarea[data-disable-with]:disabled",
                requiredInputSelector: "input[name][required]:not([disabled]),textarea[name][required]:not([disabled])",
                fileInputSelector: "input[type=file]",
                linkDisableSelector: "a[data-disable-with]",
                buttonDisableSelector: "button[data-remote][data-disable-with]",
                CSRFProtection: function (t) {
                    var n = e('meta[name="csrf-token"]').attr("content");
                    if (n) t.setRequestHeader("X-CSRF-Token", n)
                },
                refreshCSRFTokens: function () {
                    var t = e("meta[name=csrf-token]").attr("content");
                    var n = e("meta[name=csrf-param]").attr("content");
                    e('form input[name="' + n + '"]').val(t)
                },
                fire: function (t, n, r) {
                    var i = e.Event(n);
                    t.trigger(i, r);
                    return i.result !== false
                },
                confirm: function (e) {
                    return confirm(e)
                },
                ajax: function (t) {
                    return e.ajax(t)
                },
                href: function (e) {
                    return e.attr("href")
                },
                handleRemote: function (r) {
                    var i, s, o, u, a, f, l, c;
                    if (n.fire(r, "ajax:before")) {
                        u = r.data("cross-domain");
                        a = u === t ? null : u;
                        f = r.data("with-credentials") || null;
                        l = r.data("type") || e.ajaxSettings && e.ajaxSettings.dataType;
                        if (r.is("form")) {
                            i = r.attr("method");
                            s = r.attr("action");
                            o = r.serializeArray();
                            var h = r.data("ujs:submit-button");
                            if (h) {
                                o.push(h);
                                r.data("ujs:submit-button", null)
                            }
                        } else if (r.is(n.inputChangeSelector)) {
                            i = r.data("method");
                            s = r.data("url");
                            o = r.serialize();
                            if (r.data("params")) o = o + "&" + r.data("params")
                        } else if (r.is(n.buttonClickSelector)) {
                            i = r.data("method") || "get";
                            s = r.data("url");
                            o = r.serialize();
                            if (r.data("params")) o = o + "&" + r.data("params")
                        } else {
                            i = r.data("method");
                            s = n.href(r);
                            o = r.data("params") || null
                        }
                        c = {
                            type: i || "GET", data: o, dataType: l, beforeSend: function (e, i) {
                                if (i.dataType === t) {
                                    e.setRequestHeader("accept", "*/*;q=0.5, " + i.accepts.script)
                                }
                                if (n.fire(r, "ajax:beforeSend", [e, i])) {
                                    r.trigger("ajax:send", e)
                                } else {
                                    return false
                                }
                            }, success: function (e, t, n) {
                                r.trigger("ajax:success", [e, t, n])
                            }, complete: function (e, t) {
                                r.trigger("ajax:complete", [e, t])
                            }, error: function (e, t, n) {
                                r.trigger("ajax:error", [e, t, n])
                            }, crossDomain: a
                        };
                        if (f) {
                            c.xhrFields = {withCredentials: f}
                        }
                        if (s) {
                            c.url = s
                        }
                        return n.ajax(c)
                    } else {
                        return false
                    }
                },
                handleMethod: function (r) {
                    var i = n.href(r), s = r.data("method"), o = r.attr("target"),
                        u = e("meta[name=csrf-token]").attr("content"), a = e("meta[name=csrf-param]").attr("content"),
                        f = e('<form method="post" action="' + i + '"></form>'),
                        l = '<input name="_method" value="' + s + '" type="hidden" />';
                    if (a !== t && u !== t) {
                        l += '<input name="' + a + '" value="' + u + '" type="hidden" />'
                    }
                    if (o) {
                        f.attr("target", o)
                    }
                    f.hide().append(l).appendTo("body");
                    f.submit()
                },
                formElements: function (t, n) {
                    return t.is("form") ? e(t[0].elements).filter(n) : t.find(n)
                },
                disableFormElements: function (t) {
                    n.formElements(t, n.disableSelector).each(function () {
                        n.disableFormElement(e(this))
                    })
                },
                disableFormElement: function (e) {
                    var t = e.is("button") ? "html" : "val";
                    e.data("ujs:enable-with", e[t]());
                    e[t](e.data("disable-with"));
                    e.prop("disabled", true)
                },
                enableFormElements: function (t) {
                    n.formElements(t, n.enableSelector).each(function () {
                        n.enableFormElement(e(this))
                    })
                },
                enableFormElement: function (e) {
                    var t = e.is("button") ? "html" : "val";
                    if (e.data("ujs:enable-with")) e[t](e.data("ujs:enable-with"));
                    e.prop("disabled", false)
                },
                allowAction: function (e) {
                    var t = e.data("confirm"), r = false, i;
                    if (!t) {
                        return true
                    }
                    if (n.fire(e, "confirm")) {
                        r = n.confirm(t);
                        i = n.fire(e, "confirm:complete", [r])
                    }
                    return r && i
                },
                blankInputs: function (t, n, r) {
                    var i = e(), s, o, u = n || "input,textarea", a = t.find(u);
                    a.each(function () {
                        s = e(this);
                        o = s.is("input[type=checkbox],input[type=radio]") ? s.is(":checked") : s.val();
                        if (!o === !r) {
                            if (s.is("input[type=radio]") && a.filter('input[type=radio]:checked[name="' + s.attr("name") + '"]').length) {
                                return true
                            }
                            i = i.add(s)
                        }
                    });
                    return i.length ? i : false
                },
                nonBlankInputs: function (e, t) {
                    return n.blankInputs(e, t, true)
                },
                stopEverything: function (t) {
                    e(t.target).trigger("ujs:everythingStopped");
                    t.stopImmediatePropagation();
                    return false
                },
                disableElement: function (e) {
                    e.data("ujs:enable-with", e.html());
                    e.html(e.data("disable-with"));
                    e.bind("click.railsDisable", function (e) {
                        return n.stopEverything(e)
                    })
                },
                enableElement: function (e) {
                    if (e.data("ujs:enable-with") !== t) {
                        e.html(e.data("ujs:enable-with"));
                        e.removeData("ujs:enable-with")
                    }
                    e.unbind("click.railsDisable")
                }
            };
            if (n.fire(r, "rails:attachBindings")) {
                e.ajaxPrefilter(function (e, t, r) {
                    if (!e.crossDomain) {
                        n.CSRFProtection(r)
                    }
                });
                r.delegate(n.linkDisableSelector, "ajax:complete", function () {
                    n.enableElement(e(this))
                });
                r.delegate(n.buttonDisableSelector, "ajax:complete", function () {
                    n.enableFormElement(e(this))
                });
                r.delegate(n.linkClickSelector, "click.rails", function (r) {
                    var i = e(this), s = i.data("method"), o = i.data("params"), u = r.metaKey || r.ctrlKey;
                    if (!n.allowAction(i)) return n.stopEverything(r);
                    if (!u && i.is(n.linkDisableSelector)) n.disableElement(i);
                    if (i.data("remote") !== t) {
                        if (u && (!s || s === "GET") && !o) {
                            return true
                        }
                        var a = n.handleRemote(i);
                        if (a === false) {
                            n.enableElement(i)
                        } else {
                            a.error(function () {
                                n.enableElement(i)
                            })
                        }
                        return false
                    } else if (i.data("method")) {
                        n.handleMethod(i);
                        return false
                    }
                });
                r.delegate(n.buttonClickSelector, "click.rails", function (t) {
                    var r = e(this);
                    if (!n.allowAction(r)) return n.stopEverything(t);
                    if (r.is(n.buttonDisableSelector)) n.disableFormElement(r);
                    var i = n.handleRemote(r);
                    if (i === false) {
                        n.enableFormElement(r)
                    } else {
                        i.error(function () {
                            n.enableFormElement(r)
                        })
                    }
                    return false
                });
                r.delegate(n.inputChangeSelector, "change.rails", function (t) {
                    var r = e(this);
                    if (!n.allowAction(r)) return n.stopEverything(t);
                    n.handleRemote(r);
                    return false
                });
                r.delegate(n.formSubmitSelector, "submit.rails", function (r) {
                    var i = e(this), s = i.data("remote") !== t, o, u;
                    if (!n.allowAction(i)) return n.stopEverything(r);
                    if (i.attr("novalidate") == t) {
                        o = n.blankInputs(i, n.requiredInputSelector);
                        if (o && n.fire(i, "ajax:aborted:required", [o])) {
                            return n.stopEverything(r)
                        }
                    }
                    if (s) {
                        u = n.nonBlankInputs(i, n.fileInputSelector);
                        if (u) {
                            setTimeout(function () {
                                n.disableFormElements(i)
                            }, 13);
                            var a = n.fire(i, "ajax:aborted:file", [u]);
                            if (!a) {
                                setTimeout(function () {
                                    n.enableFormElements(i)
                                }, 13)
                            }
                            return a
                        }
                        n.handleRemote(i);
                        return false
                    } else {
                        setTimeout(function () {
                            n.disableFormElements(i)
                        }, 13)
                    }
                });
                r.delegate(n.formInputClickSelector, "click.rails", function (t) {
                    var r = e(this);
                    if (!n.allowAction(r)) return n.stopEverything(t);
                    var i = r.attr("name"), s = i ? {name: i, value: r.val()} : null;
                    r.closest("form").data("ujs:submit-button", s)
                });
                r.delegate(n.formSubmitSelector, "ajax:send.rails", function (t) {
                    if (this == t.target) n.disableFormElements(e(this))
                });
                r.delegate(n.formSubmitSelector, "ajax:complete.rails", function (t) {
                    if (this == t.target) n.enableFormElements(e(this))
                });
                e(function () {
                    n.refreshCSRFTokens()
                })
            }
        })(jQuery)
    </script>
    <script>
        jQuery(document).ready(function ($) {

            $.ajaxSetup({
                beforeSend: function (xhr, settings) {
                    console.log('beforesend');
                    settings.data += "&_token={{ csrf_token() }}";
                }
            });

            /*$('.editable').editable().on('hidden', function (e, reason) {
                var locale = $(this).data('locale');
                if (reason === 'save') {
                    $(this).removeClass('status-0').addClass('status-1');
                }
                if (reason === 'save' || reason === 'nochange') {
                    var $next = $(this).closest('tr').next().find('.editable.locale-' + locale);
                    setTimeout(function () {
                        //$next.editable('show');
                    }, 300);
                }
            });*/


            $('.group-select').on('change', function () {
                var group = $(this).val();
                if (group) {
                    window.location.href = '{{ action('\App\Http\Controllers\TranslationManagerController@getView') }}/' + $(this).val();
                } else {
                    window.location.href = '{{ action('\App\Http\Controllers\TranslationManagerController@getIndex') }}';
                }
                ;
            });

            $("a.delete-key").on('confirm:complete', function (event, result) {
                if (result) {
                    var row = $(this).closest('tr');
                    var url = $(this).attr('href');
                    var id = row.attr('id');
                    $.post(url, {id: id}, function () {
                        row.remove();
                    });
                }
                return false;
            });

            $('.form-import').on('ajax:success', function (e, data) {
                $('div.success-import strong.counter').text(data.counter);
                $('div.success-import').slideDown();
                window.location.reload();
            });

            $('.form-find').on('ajax:success', function (e, data) {
                $('div.success-find strong.counter').text(data.counter);
                $('div.success-find').slideDown();
                window.location.reload();
            });

            $('.form-publish').on('ajax:success', function (e, data) {
                $('div.success-publish').slideDown();
            });

            $('.form-publish-all').on('ajax:success', function (e, data) {
                $('div.success-publish-all').slideDown();
            });
            $('.enable-auto-translate-group').click(function (event) {
                event.preventDefault();
                $('.autotranslate-block-group').removeClass('hidden');
                $('.enable-auto-translate-group').addClass('hidden');
            })
            $('#base-locale').change(function (event) {
                console.log($(this).val());
                $.cookie('base_locale', $(this).val());
            })
            if (typeof $.cookie('base_locale') !== 'undefined') {
                $('#base-locale').val($.cookie('base_locale'));
            }

        })
    </script>
@stop
