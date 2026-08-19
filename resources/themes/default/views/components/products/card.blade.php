<v-product-card
    {{ $attributes }}
    :product="product"
>
</v-product-card>

@pushOnce('scripts')
    <script type="text/x-template" id="v-product-card-template">
        <article class="icm-product-card" :class="{ 'icm-product-card--list': mode === 'list' }">
            <a
                class="icm-product-card__media"
                :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)"
                :aria-label="product.name"
            >
                <x-shop::media.images.lazy
                    ::src="product.base_image.medium_image_url"
                    ::srcset="`${product.base_image.small_image_url} 150w, ${product.base_image.medium_image_url} 300w`"
                    sizes="(max-width: 768px) 50vw, (max-width: 1200px) 33vw, 300px"
                    ::key="product.id"
                    ::index="product.id"
                    width="300"
                    height="312"
                    ::alt="product.base_image.alt"
                />

                <span class="icm-product-card__badge icm-product-card__badge--sale" v-if="product.on_sale">
                    @lang('shop::app.components.products.card.sale')
                </span>

                <span class="icm-product-card__badge" v-else-if="product.is_new">
                    @lang('shop::app.components.products.card.new')
                </span>
            </a>

            <div class="icm-product-card__quick-actions">
                @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                    <span
                        class="icm-product-card__icon"
                        role="button"
                        aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                        tabindex="0"
                        :class="product.is_wishlist ? 'icon-heart-fill text-red-500' : 'icon-heart'"
                        @click="addToWishlist()"
                    ></span>
                @endif

                @if (core()->getConfigData('catalog.products.settings.compare_option'))
                    <span
                        class="icon-compare icm-product-card__icon"
                        role="button"
                        aria-label="@lang('shop::app.components.products.card.add-to-compare')"
                        tabindex="0"
                        @click="addToCompare(product.id)"
                    ></span>
                @endif
            </div>

            <div class="icm-product-card__content">
                <a
                    class="icm-product-card__name"
                    :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)"
                >
                    @{{ product.name }}
                </a>

                <div class="icm-product-card__price" v-html="product.price_html"></div>

                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                    <button
                        class="primary-button icm-product-card__add"
                        :disabled="! product.is_saleable || isAddingToCart"
                        @click="addToCart()"
                    >
                        @lang('shop::app.components.products.card.add-to-cart')
                    </button>
                @endif
            </div>
        </article>
    </script>

    <script type="module">
        app.component('v-product-card', {
            template: '#v-product-card-template',

            props: ['mode', 'product'],

            data() {
                return {
                    isCustomer: '{{ auth()->guard('customer')->check() }}',
                    isAddingToCart: false,
                }
            },

            methods: {
                addToWishlist() {
                    if (! this.isCustomer) {
                        window.location.href = "{{ route('shop.customer.session.index') }}";

                        return;
                    }

                    this.$axios.post(`{{ route('shop.api.customers.account.wishlist.store') }}`, { product_id: this.product.id })
                        .then(response => {
                            this.product.is_wishlist = ! this.product.is_wishlist;
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                        });
                },

                addToCompare(productId) {
                    if (this.isCustomer) {
                        this.$axios.post('{{ route('shop.api.compare.store') }}', { product_id: productId })
                            .then(response => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {
                                const message = error.response?.data?.data?.message ?? error.response?.data?.message;
                                this.$emitter.emit('add-flash', { type: 'warning', message });
                            });

                        return;
                    }

                    const items = this.getStorageValue();

                    if (items.includes(productId)) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.components.products.card.already-in-compare')" });

                        return;
                    }

                    items.push(productId);
                    localStorage.setItem('compare_items', JSON.stringify(items));
                    this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });
                },

                getStorageValue() {
                    const value = localStorage.getItem('compare_items');

                    return value ? JSON.parse(value) : [];
                },

                addToCart() {
                    this.isAddingToCart = true;

                    this.$axios.post('{{ route('shop.api.checkout.cart.store') }}', {
                        quantity: 1,
                        product_id: this.product.id,
                    })
                        .then(response => {
                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data);
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: error.response?.data?.message });

                            if (error.response?.data?.redirect_uri) {
                                window.location.href = error.response.data.redirect_uri;
                            }
                        })
                        .finally(() => {
                            this.isAddingToCart = false;
                        });
                },
            },
        });
    </script>
@endPushOnce
