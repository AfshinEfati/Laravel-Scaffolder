import { defineComponent, computed, mergeProps, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderAttr, ssrRenderSlot } from "vue/server-renderer";
import { b as useRuntimeConfig } from "../server.mjs";
import "/app/docs-site/node_modules/ofetch/dist/node.mjs";
import "#internal/nuxt/paths";
import "/app/docs-site/node_modules/hookable/dist/index.mjs";
import "/app/docs-site/node_modules/unctx/dist/index.mjs";
import "/app/docs-site/node_modules/h3/dist/index.mjs";
import "vue-router";
import "/app/docs-site/node_modules/defu/dist/defu.mjs";
import "/app/docs-site/node_modules/ufo/dist/index.mjs";
import "/app/docs-site/node_modules/nuxt/node_modules/@unhead/vue/dist/index.mjs";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ProseH2",
  __ssrInlineRender: true,
  props: {
    id: {}
  },
  setup(__props) {
    const props = __props;
    const { headings } = useRuntimeConfig().public.mdc;
    const generate = computed(() => props.id && (typeof headings?.anchorLinks === "boolean" && headings?.anchorLinks === true || typeof headings?.anchorLinks === "object" && headings?.anchorLinks?.h2));
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<h2${ssrRenderAttrs(mergeProps({
        id: props.id
      }, _attrs))}>`);
      if (props.id && unref(generate)) {
        _push(`<a${ssrRenderAttr("href", `#${props.id}`)}>`);
        ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
        _push(`</a>`);
      } else {
        ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      }
      _push(`</h2>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("node_modules/@nuxtjs/mdc/dist/runtime/components/prose/ProseH2.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
//# sourceMappingURL=ProseH2-Bgv2wVdU.js.map
