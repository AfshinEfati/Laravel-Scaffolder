import _sfc_main$1 from './ContentRenderer-zE7hn5dv.mjs';
import { _ as __nuxt_component_0 } from './nuxt-link-YJU-IzoS.mjs';
import { withAsyncContext, unref, mergeProps, withCtx, createTextVNode, useSSRContext } from 'file:///app/docs-site/node_modules/vue/index.mjs';
import { ssrRenderAttrs, ssrRenderComponent } from 'file:///app/docs-site/node_modules/vue/server-renderer/index.mjs';
import { a as useRoute, n as navigateTo } from './server.mjs';
import { u as useAsyncData, q as queryContent } from './query-RT5V1MJ1.mjs';
import { u as useContentHead } from './head-3IpIxPIF.mjs';
import './ContentRendererMarkdown-DIHkEoZ6.mjs';
import 'file:///app/docs-site/node_modules/destr/dist/index.mjs';
import 'file:///app/docs-site/node_modules/scule/dist/index.mjs';
import 'file:///app/docs-site/node_modules/property-information/index.js';
import './node-DPfXEbjB.mjs';
import './preview-CxS_JB1H.mjs';
import 'file:///app/docs-site/node_modules/nuxt/node_modules/cookie-es/dist/index.mjs';
import 'file:///app/docs-site/node_modules/h3/dist/index.mjs';
import 'file:///app/docs-site/node_modules/nuxt/node_modules/ohash/dist/index.mjs';
import 'file:///app/docs-site/node_modules/klona/dist/index.mjs';
import 'file:///app/docs-site/node_modules/ufo/dist/index.mjs';
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
import 'file:///app/docs-site/node_modules/perfect-debounce/dist/index.mjs';
import 'file:///app/docs-site/node_modules/ohash/dist/index.mjs';

const _sfc_main = {
  __name: "[...slug]",
  __ssrInlineRender: true,
  async setup(__props) {
    let __temp, __restore;
    const route = useRoute();
    const { data: page } = ([__temp, __restore] = withAsyncContext(() => useAsyncData(`page-${route.path}`, () => queryContent(route.path).findOne())), __temp = await __temp, __restore(), __temp);
    if (!page.value && route.path === "/") {
      navigateTo("/en");
    }
    useContentHead(page);
    return (_ctx, _push, _parent, _attrs) => {
      const _component_ContentRenderer = _sfc_main$1;
      const _component_NuxtLink = __nuxt_component_0;
      if (unref(page)) {
        _push(`<div${ssrRenderAttrs(_attrs)}><article class="prose prose-slate dark:prose-invert max-w-none">`);
        _push(ssrRenderComponent(_component_ContentRenderer, { value: unref(page) }, null, _parent));
        _push(`</article></div>`);
      } else {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex flex-col items-center justify-center h-64 gap-4" }, _attrs))}><p class="text-gray-500 text-xl font-medium">Page not found.</p>`);
        _push(ssrRenderComponent(_component_NuxtLink, {
          to: "/en",
          class: "text-blue-600 hover:underline"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`Go to Home`);
            } else {
              return [
                createTextVNode("Go to Home")
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div>`);
      }
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("pages/[...slug].vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};

export { _sfc_main as default };
//# sourceMappingURL=_...slug_-mxViu1qb.mjs.map
