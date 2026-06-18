import { _ as __nuxt_component_0 } from './nuxt-link-YJU-IzoS.mjs';
import { computed, withAsyncContext, ref, mergeProps, unref, withCtx, createTextVNode, toDisplayString, createVNode, useSSRContext } from 'file:///app/docs-site/node_modules/vue/index.mjs';
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate, ssrRenderClass, ssrRenderList, ssrRenderSlot, ssrRenderAttr } from 'file:///app/docs-site/node_modules/vue/server-renderer/index.mjs';
import { u as useAsyncData, _ as __nuxt_component_2, q as queryContent } from './query-RT5V1MJ1.mjs';
import { a as useRoute, u as useHead, c as useState } from './server.mjs';
import { f as fetchContentNavigation } from './navigation-CNamej42.mjs';
import 'file:///app/docs-site/node_modules/ufo/dist/index.mjs';
import 'file:///app/docs-site/node_modules/perfect-debounce/dist/index.mjs';
import 'file:///app/docs-site/node_modules/ohash/dist/index.mjs';
import './preview-CxS_JB1H.mjs';
import 'file:///app/docs-site/node_modules/nuxt/node_modules/cookie-es/dist/index.mjs';
import 'file:///app/docs-site/node_modules/h3/dist/index.mjs';
import 'file:///app/docs-site/node_modules/destr/dist/index.mjs';
import 'file:///app/docs-site/node_modules/nuxt/node_modules/ohash/dist/index.mjs';
import 'file:///app/docs-site/node_modules/klona/dist/index.mjs';
import 'file:///app/docs-site/node_modules/ofetch/dist/node.mjs';
import '../nitro/nitro.mjs';
import 'file:///app/docs-site/node_modules/unified/index.js';
import 'file:///app/docs-site/node_modules/remark-parse/index.js';
import 'file:///app/docs-site/node_modules/remark-rehype/index.js';
import 'file:///app/docs-site/node_modules/remark-mdc/dist/index.mjs';
import 'file:///app/docs-site/node_modules/defu/dist/defu.mjs';
import 'file:///app/docs-site/node_modules/remark-gfm/index.js';
import 'file:///app/docs-site/node_modules/rehype-external-links/index.js';
import 'file:///app/docs-site/node_modules/rehype-sort-attribute-values/index.js';
import 'file:///app/docs-site/node_modules/rehype-sort-attributes/index.js';
import 'file:///app/docs-site/node_modules/rehype-raw/index.js';
import 'file:///app/docs-site/node_modules/detab/index.js';
import 'file:///app/docs-site/node_modules/scule/dist/index.mjs';
import 'file:///app/docs-site/node_modules/micromark-util-sanitize-uri/index.js';
import 'file:///app/docs-site/node_modules/hast-util-to-string/index.js';
import 'file:///app/docs-site/node_modules/github-slugger/index.js';
import 'file:///app/docs-site/node_modules/hookable/dist/index.mjs';
import 'file:///app/docs-site/node_modules/node-mock-http/dist/index.mjs';
import 'file:///app/docs-site/node_modules/unstorage/dist/index.mjs';
import 'file:///app/docs-site/node_modules/unstorage/drivers/fs.mjs';
import 'node:crypto';
import 'file:///app/docs-site/node_modules/unstorage/drivers/fs-lite.mjs';
import 'file:///app/docs-site/node_modules/unstorage/drivers/lru-cache.mjs';
import 'file:///app/docs-site/node_modules/nitropack/node_modules/ohash/dist/index.mjs';
import 'file:///app/docs-site/node_modules/unctx/dist/index.mjs';
import 'file:///app/docs-site/node_modules/radix3/dist/index.mjs';
import 'node:fs';
import 'node:url';
import 'file:///app/docs-site/node_modules/pathe/dist/index.mjs';
import 'file:///app/docs-site/node_modules/vue-router/vue-router.node.mjs';
import 'file:///app/docs-site/node_modules/nuxt/node_modules/unhead/dist/utils.mjs';

const _sfc_main$1 = {
  __name: "Search",
  __ssrInlineRender: true,
  async setup(__props) {
    let __temp, __restore;
    const { data: searchResults } = ([__temp, __restore] = withAsyncContext(() => useAsyncData("search", () => searchContent(""))), __temp = await __temp, __restore(), __temp);
    const q = ref("");
    const results = computed(() => {
      if (!q.value) return [];
      return searchResults.value.filter(
        (item) => {
          var _a;
          return item.title.toLowerCase().includes(q.value.toLowerCase()) || ((_a = item.description) == null ? void 0 : _a.toLowerCase().includes(q.value.toLowerCase()));
        }
      ).slice(0, 10);
    });
    const isFa = computed(() => useRoute().path.startsWith("/fa"));
    return (_ctx, _push, _parent, _attrs) => {
      const _component_NuxtLink = __nuxt_component_0;
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "relative group" }, _attrs))}><div class="relative"><input${ssrRenderAttr("value", unref(q))} type="text"${ssrRenderAttr("placeholder", unref(isFa) ? "\u062C\u0633\u062A\u062C\u0648..." : "Search...")} class="w-40 lg:w-64 bg-gray-100 dark:bg-gray-800 border-none rounded-lg py-2 px-4 text-sm focus:ring-2 focus:ring-blue-500 transition-all outline-none">`);
      if (unref(results).length > 0) {
        _push(`<div class="absolute top-full mt-2 w-full bg-white dark:bg-gray-800 shadow-xl rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-50"><!--[-->`);
        ssrRenderList(unref(results), (item) => {
          _push(ssrRenderComponent(_component_NuxtLink, {
            key: item._path,
            to: item._path,
            onClick: ($event) => q.value = "",
            class: "block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b last:border-none border-gray-100 dark:border-gray-700"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`<div class="text-sm font-bold"${_scopeId}>${ssrInterpolate(item.title)}</div><div class="text-xs text-gray-500 truncate"${_scopeId}>${ssrInterpolate(item.description)}</div>`);
              } else {
                return [
                  createVNode("div", { class: "text-sm font-bold" }, toDisplayString(item.title), 1),
                  createVNode("div", { class: "text-xs text-gray-500 truncate" }, toDisplayString(item.description), 1)
                ];
              }
            }),
            _: 2
          }, _parent));
        });
        _push(`<!--]--></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div>`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("components/Search.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const useColorMode = () => {
  return useState("color-mode").value;
};
const _sfc_main = {
  __name: "default",
  __ssrInlineRender: true,
  async setup(__props) {
    let __temp, __restore;
    const route = useRoute();
    const isFa = computed(() => route.path.startsWith("/fa"));
    const dir = computed(() => isFa.value ? "rtl" : "ltr");
    useHead({
      htmlAttrs: {
        lang: computed(() => isFa.value ? "fa" : "en"),
        dir
      }
    });
    const { data: navigation } = ([__temp, __restore] = withAsyncContext(() => useAsyncData("navigation", () => {
      const locale = isFa.value ? "fa" : "en";
      return fetchContentNavigation(queryContent(locale));
    })), __temp = await __temp, __restore(), __temp);
    useColorMode();
    const isSidebarOpen = ref(false);
    return (_ctx, _push, _parent, _attrs) => {
      const _component_NuxtLink = __nuxt_component_0;
      const _component_Search = _sfc_main$1;
      const _component_ClientOnly = __nuxt_component_2;
      _push(`<div${ssrRenderAttrs(mergeProps({
        dir: unref(dir),
        class: "min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans"
      }, _attrs))}><header class="sticky top-0 z-40 w-full backdrop-blur border-b border-gray-200 dark:border-gray-800 bg-white/75 dark:bg-gray-900/75"><div class="container mx-auto px-4 h-16 flex items-center justify-between"><div class="flex items-center gap-4"><button class="lg:hidden p-2"><span class="sr-only">Toggle Sidebar</span><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg></button>`);
      _push(ssrRenderComponent(_component_NuxtLink, {
        to: unref(isFa) ? "/fa" : "/en",
        class: "text-xl font-bold tracking-tight"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` Laravel Scaffolder `);
          } else {
            return [
              createTextVNode(" Laravel Scaffolder ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><div class="flex items-center gap-2 lg:gap-4">`);
      _push(ssrRenderComponent(_component_Search, null, null, _parent));
      _push(ssrRenderComponent(_component_NuxtLink, {
        to: unref(isFa) ? "/en" : "/fa",
        class: "text-sm font-medium hover:text-blue-600 transition-colors"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(unref(isFa) ? "English" : "\u0641\u0627\u0631\u0633\u06CC")}`);
          } else {
            return [
              createTextVNode(toDisplayString(unref(isFa) ? "English" : "\u0641\u0627\u0631\u0633\u06CC"), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">`);
      _push(ssrRenderComponent(_component_ClientOnly, null, {}, _parent));
      _push(`</button><a href="https://github.com/AfshinEfati/Laravel-Scaffolder" target="_blank" class="hidden sm:block p-2 hover:text-blue-600 transition-colors"> GitHub </a></div></div></header><div class="container mx-auto px-4 flex flex-col lg:flex-row gap-8 py-8"><aside class="${ssrRenderClass([
        "fixed lg:static inset-y-0 z-30 w-64 transform transition-transform duration-300 lg:translate-x-0 bg-white dark:bg-gray-900 lg:bg-transparent overflow-y-auto pt-20 lg:pt-0",
        unref(isFa) ? unref(isSidebarOpen) ? "right-0 shadow-xl" : "translate-x-full lg:translate-x-0" : unref(isSidebarOpen) ? "left-0 shadow-xl" : "-translate-x-full lg:translate-x-0"
      ])}"><nav class="space-y-6"><!--[-->`);
      ssrRenderList(unref(navigation), (group) => {
        _push(`<div><h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 px-3">${ssrInterpolate(group.title)}</h3><ul class="space-y-1"><!--[-->`);
        ssrRenderList(group.children, (item) => {
          _push(`<li>`);
          _push(ssrRenderComponent(_component_NuxtLink, {
            to: item._path,
            class: "block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all duration-200",
            "active-class": "!text-blue-600 !bg-blue-50 dark:!bg-blue-900/20 font-bold"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`${ssrInterpolate(item.title)}`);
              } else {
                return [
                  createTextVNode(toDisplayString(item.title), 1)
                ];
              }
            }),
            _: 2
          }, _parent));
          _push(`</li>`);
        });
        _push(`<!--]--></ul></div>`);
      });
      _push(`<!--]--></nav></aside><main class="flex-1 min-w-0">`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</main></div>`);
      if (unref(isSidebarOpen)) {
        _push(`<div class="fixed inset-0 z-20 bg-black/50 lg:hidden backdrop-blur-sm"></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("layouts/default.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};

export { _sfc_main as default };
//# sourceMappingURL=default-zzyr9w-w.mjs.map
