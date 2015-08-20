<div class="row">
    {{-- left sidebar --}}
    <div class="col-sm-8 col-md-8">
        <h1>{{{ $business->name }}}</h1>
        <address>{{{ $business->full_address }}}</address>

    @if ($business->images->isEmpty() === false)
        <!-- Slider main container -->
        <div class="slideshow swiper-container" id="js-swiper-{{ $business->user_id }}">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
            @foreach ($business->images as $image)
                <div class="swiper-slide text-center">
                    <img style="max-width: 100%;" src="{{ $image->getPublicUrl() }}" alt="">
                </div>
            @endforeach
            </div>
        </div>
    @else
        <div class="slideshow">
            <p class="text-center"><img src="{{ $business->image }}" alt="{{{ $business->name }}}"></p>
        </div>
    @endif

        <h3 class="sub-heading">{{ trans('home.business.about') }}</h3>
        <div class="description">
            {{ $business->description_html }}
        </div>

        <h3 class="sub-heading">Book your service online</h3>
        <div class="cp-booking-form">
            <div class="content">
                <div class="panel-group" id="js-cp-booking-form-categories" role="tablist">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#js-cp-booking-form-categories" href="#js-category-1">
                                    Category name
                                    <span class="pull-right">9 services</span>
                                </a>
                            </h4>
                        </div>
                        <div id="js-category-1" class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <div class="panel-group panel-group-service" id="js-cp-booking-form-categories-1" role="tablist">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#js-cp-booking-form-categories-1" href="#js-service-1">
                                                    Service with custom time
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="js-service-1" class="panel-collapse collapse in" role="tabpanel">
                                            <div class="panel-body">
                                                <div class="service">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <div class="service-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima aliquid fugit beatae labore provident unde, quasi, tempore, reiciendis amet inventore, delectus recusandae et dolorem ut maxime autem obcaecati pariatur corporis.</div>
                                                            <p>60min/ 70&euro;</p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button class="btn btn-orange pull-right">Select</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="service">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <div class="service-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima aliquid fugit beatae labore provident unde, quasi, tempore, reiciendis amet inventore, delectus recusandae et dolorem ut maxime autem obcaecati pariatur corporis.</div>
                                                            <p>60min/ 70&euro;</p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button class="btn btn-orange pull-right">Select</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-group-service">
                                    <div class="single-service">
                                        <h4 class="panel-title">Service name</h4>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="service-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima aliquid fugit beatae labore provident unde, quasi, tempore, reiciendis amet inventore, delectus recusandae et dolorem ut maxime autem obcaecati pariatur corporis.</div>
                                                <p>60min/ 70&euro;</p>
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-orange pull-right">Select</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#js-cp-booking-form-categories" href="#js-category-2">
                                    Category 2
                                    <span class="pull-right">9 services</span>
                                </a>
                            </h4>
                        </div>
                        <div id="js-category-2" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <div class="panel-group-service">
                                    <div class="single-service">
                                        <h4 class="panel-title">Service name</h4>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="service-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima aliquid fugit beatae labore provident unde, quasi, tempore, reiciendis amet inventore, delectus recusandae et dolorem ut maxime autem obcaecati pariatur corporis.</div>
                                                <p>60min/ 70&euro;</p>
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-orange pull-right">Select</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="navigation">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="#" class="btn btn-orange hidden">Go back</a>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="#" class="btn btn-orange pull-right">Continue</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="cp-booking-form">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4"><strong>Choose the employee</strong></div>
                    <div class="col-sm-2">Employee1</div>
                    <div class="col-sm-2">Employee2</div>
                    <div class="col-sm-2">Employee3</div>
                    <div class="col-sm-2">Employee4</div>
                </div>

                <div class="row">
                    <div class="col-sm-1"><i class="glyphicon glyphicon-chevron-left"></i></div>
                    <div class="col-sm-10">
                        <ul class="dates">
                            <li>17.8</li>
                            <li>17.8</li>
                            <li>17.8</li>
                            <li>17.8</li>
                            <li>17.8</li>
                            <li>17.8</li>
                            <li>17.8</li>
                        </ul>
                    </div>
                    <div class="col-sm-1 text-right"><i class="glyphicon glyphicon-chevron-right"></i></div>
                </div>

                <div class="row">
                    <div class="col-sm-offset-1 col-sm-10">
                        <ul class="time-options">
                            <li>08:00</li>
                            <li>09:00</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="navigation">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="#" class="btn btn-orange">Go back</a>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="#" class="btn btn-orange pull-right">Continue</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- right sidebar --}}
    <div class="col-sm-4 col-md-4">
    @if ($business->is_booking_disabled)
        @include ('front.contact.form', ['business' => $business])
    @else
        @if ($business->isUsingAS)
        <div class="box">
            {{-- `$inhouse = true` means that we'll show login/register secion in step 4 --}}
            <input type="hidden" id="business_id" value="{{ $business->id }}">
            <input type="hidden" id="business_hash" value="{{ $business->user->hash }}">
            @include('modules.as.embed.layout-3.main', ['inhouse' => false, 'hash' => $business->user->hash, 'allInput' => ['l' => 3, 'hash' => $business->user->hash, 'src' => 'inhouse']])
        </div>
        @endif
    @endif

        <h3 class="sub-heading">{{ trans('home.business.map') }}</h3>
        <div data-lat="{{ $business->lat }}" data-lng="{{ $business->lng }}" id="js-map-{{ $business->user_id }}" class="small-map"></div>

        <div class="row">
            <div class="col-sm-6 col-md-6">
                <h3 class="sub-heading">{{ trans('home.business.openning_hours') }}</h3>
                <table class="table table-working-hours">
                    <tbody>
                    @foreach ($business->working_hours_array as $day => $value)
                        <tr>
                            <td>{{ trans('common.short.'.$day) }}</td>
                        @if (isset($value['hidden']) && (bool) $value['hidden'] === true)
                            <td colspan="2">
                                @if (!empty($value['extra'])) {{{ $value['extra'] }}}
                                @endif
                            </td>
                        @else
                            <td><p>{{ $value['formatted'] or '' }}</p>
                                @if (!empty($value['extra'])) <span class="text-info">{{{ $value['extra'] }}}</span>
                                @endif
                            </td>
                        @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6 col-md-6">
                <h3 class="sub-heading">{{ trans('home.business.contact.index') }}</h3>

                <p><strong>{{ trans('home.business.phone') }}</strong></p>
                <p>{{{ $business->phone }}}</p>

                <p><strong>{{ trans('home.business.email') }}</strong></p>
                <p>{{{ $business->user->email }}}</p>
            </div>
        </div>
    </div>
</div>
