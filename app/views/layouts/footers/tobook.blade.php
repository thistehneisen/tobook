<footer class="container-fluid footer lv hidden-print">
    <div class="row">
        <div class="col-sm-7 col-sm-offset-1">
            <h2 class="heading">Pieteikties jaunumiem</h2>

            <div class="upper">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- Begin MailChimp Signup Form -->
                        <div class="form-subscribe" id="mc_embed_signup">
                            <form action="//tobook.us11.list-manage.com/subscribe/post?u=70f4e6554b0f5f56638d31c0f&amp;id=fb962672f0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate form-inline" target="_blank" novalidate>
                                <div class="form-group" id="mc_embed_signup_scroll">
                                    <label class="sr-only" for="mce-EMAIL">Subscribe to our mailing list</label>
                                    <input type="email" value="" name="EMAIL" class="form-control" id="mce-EMAIL" placeholder="Jūsu e-pasta adrese" required>
                                    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                    <div style="position: absolute; left: -5000px;"><input type="text" name="b_70f4e6554b0f5f56638d31c0f_fb962672f0" tabindex="-1" value=""></div>
                                </div>
                                <input type="submit" value="PASAŪTĪT" name="subscribe" id="mc-embedded-subscribe" class="btn btn-orange">
                            </form>
                        </div>

                        <!--End mc_embed_signup-->
                    </div>
                    <div class="col-sm-6">
                        <ul class="list-unstyled list-inline">
                            @foreach (Settings::group('social') as $name => $url)
                                @if ($url)
                                <li><a href="{{{ $url }}}" target="_blank"><i class="fa fa-2x fa-{{{ $name }}}"></i></a></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <h2 class="heading">Par ToBook.lv</h2>
                    <p>Tiešsaistes skaistumkopšanas salonu pakalpojumu rezervējumu servisu nodrošina AS DELFI, kas ir pakļauta Latvijas likumdošanai. Uzņēmuma birojs atrodas Mūkusalas 41B-8 Rīgā, Latvijā. Uzņēmuma reģistrācijas numurs ir 40003504352.</p>
                    <p>Tālrunis kontaktiem: 67784050</p>
                    <p>e-pasts: <a href="mailto:info@tobook.lv">info@tobook.lv</a></p>
                </div>
                <div class="col-sm-6">
                    <h2 class="heading">Informācija pircējiem</h2>
                    <p><a href="{{ route('terms') }}">Noteikumi un nosacījumi</a></p>
                    <p><a href="{{ route('policy') }}">Sīkdatņu izmantošana</a></p>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="contact-form">
                <h2 class="heading">Kontaktu forma</h2>
                {{ Form::open(['route' => 'contact', 'id' => 'form-contact']) }}
                    <div class="alert alert-danger" style="display: none;"></div>
                    <div class="alert alert-success" style="display: none;">
                        <p>@lang('home.contact.sent')</p>
                    </div>
                    <div class="form-group">
                        <label for="name" class="sr-only">Jūsu e-pasta adrese</label>
                        <input name="email" type="text" class="form-control" placeholder="Jūsu e-pasta adrese *">
                    </div>
                    <div class="form-group">
                        <textarea name="message" placeholder="Ierakstiet, lūdzu, savu jautājumu vai ierosinājumu šeit *" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-orange btn-lg pull-right">NOSŪTĪT</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</footer>
