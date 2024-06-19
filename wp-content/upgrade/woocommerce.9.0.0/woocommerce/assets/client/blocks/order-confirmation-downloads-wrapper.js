(()=>{var e,o,t,r={7135:(e,o,t)=>{"use strict";t.r(o);var r=t(1609);const n=window.wp.blocks,a=window.wp.blockEditor;var s=t(7104),i=t(8474);const c=window.wc.wcSettings;var l=t(9019),d=t.n(l);t(9314);const p=JSON.parse('{"name":"woocommerce/order-confirmation-downloads-wrapper","version":"1.0.0","title":"Downloads Section","description":"Display the downloadable products section.","category":"woocommerce","keywords":["WooCommerce"],"attributes":{"heading":{"type":"string"}},"supports":{"multiple":false,"align":["wide","full"],"html":false,"spacing":{"padding":true,"margin":true,"__experimentalDefaultControls":{"margin":false,"padding":false}}},"textdomain":"woocommerce","apiVersion":2,"$schema":"https://schemas.wp.org/trunk/block.json"}'),u={heading:{type:"string",default:(0,t(7723).__)("Downloads","woocommerce")}};(0,n.registerBlockType)(p,{icon:{src:(0,r.createElement)(s.A,{icon:i.A,className:"wc-block-editor-components-block-icon"})},edit:({attributes:e,setAttributes:o})=>{const t=(0,a.useBlockProps)(),n=(0,c.getSetting)("storeHasDownloadableProducts");return(0,r.createElement)("div",{...t,className:d()(t.className,{"store-has-downloads":n})},(0,r.createElement)(a.InnerBlocks,{allowedBlocks:["core/heading"],template:[["core/heading",{level:3,style:{typography:{fontSize:"24px"}},content:e.heading||"",onChangeContent:e=>o({heading:e})}],["woocommerce/order-confirmation-downloads",{lock:{remove:!0}}]]}))},save:()=>(0,r.createElement)(a.InnerBlocks.Content,null),attributes:u})},9314:()=>{},1609:e=>{"use strict";e.exports=window.React},6087:e=>{"use strict";e.exports=window.wp.element},7723:e=>{"use strict";e.exports=window.wp.i18n},5573:e=>{"use strict";e.exports=window.wp.primitives}},n={};function a(e){var o=n[e];if(void 0!==o)return o.exports;var t=n[e]={exports:{}};return r[e].call(t.exports,t,t.exports,a),t.exports}a.m=r,e=[],a.O=(o,t,r,n)=>{if(!t){var s=1/0;for(d=0;d<e.length;d++){for(var[t,r,n]=e[d],i=!0,c=0;c<t.length;c++)(!1&n||s>=n)&&Object.keys(a.O).every((e=>a.O[e](t[c])))?t.splice(c--,1):(i=!1,n<s&&(s=n));if(i){e.splice(d--,1);var l=r();void 0!==l&&(o=l)}}return o}n=n||0;for(var d=e.length;d>0&&e[d-1][2]>n;d--)e[d]=e[d-1];e[d]=[t,r,n]},a.n=e=>{var o=e&&e.__esModule?()=>e.default:()=>e;return a.d(o,{a:o}),o},t=Object.getPrototypeOf?e=>Object.getPrototypeOf(e):e=>e.__proto__,a.t=function(e,r){if(1&r&&(e=this(e)),8&r)return e;if("object"==typeof e&&e){if(4&r&&e.__esModule)return e;if(16&r&&"function"==typeof e.then)return e}var n=Object.create(null);a.r(n);var s={};o=o||[null,t({}),t([]),t(t)];for(var i=2&r&&e;"object"==typeof i&&!~o.indexOf(i);i=t(i))Object.getOwnPropertyNames(i).forEach((o=>s[o]=()=>e[o]));return s.default=()=>e,a.d(n,s),n},a.d=(e,o)=>{for(var t in o)a.o(o,t)&&!a.o(e,t)&&Object.defineProperty(e,t,{enumerable:!0,get:o[t]})},a.o=(e,o)=>Object.prototype.hasOwnProperty.call(e,o),a.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.j=1323,(()=>{var e={1323:0};a.O.j=o=>0===e[o];var o=(o,t)=>{var r,n,[s,i,c]=t,l=0;if(s.some((o=>0!==e[o]))){for(r in i)a.o(i,r)&&(a.m[r]=i[r]);if(c)var d=c(a)}for(o&&o(t);l<s.length;l++)n=s[l],a.o(e,n)&&e[n]&&e[n][0](),e[n]=0;return a.O(d)},t=self.webpackChunkwebpackWcBlocksMainJsonp=self.webpackChunkwebpackWcBlocksMainJsonp||[];t.forEach(o.bind(null,0)),t.push=o.bind(null,t.push.bind(t))})();var s=a.O(void 0,[94],(()=>a(7135)));s=a.O(s),((this.wc=this.wc||{}).blocks=this.wc.blocks||{})["order-confirmation-downloads-wrapper"]=s})();