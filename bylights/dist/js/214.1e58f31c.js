"use strict";(self["webpackChunkbylights"]=self["webpackChunkbylights"]||[]).push([[214],{2214:function(e,a,l){l.r(a),l.d(a,{default:function(){return k}});var t=l(6768),n=l(4232);const s={key:0},u=(0,t.Lk)("h1",null,"BADGES DETAILS",-1),d={key:1},i=(0,t.Lk)("p",null,"Loading badge details...",-1),r=[i];function c(e,a,l,i,c,g){return i.badge?((0,t.uX)(),(0,t.CE)("div",s,[u,(0,t.Lk)("h2",null,(0,n.v_)(i.badge.title),1),(0,t.Lk)("p",null,"The badge ID is: "+(0,n.v_)(i.id),1),(0,t.Lk)("p",null,"Dettagli del badge: "+(0,n.v_)(i.badge.details),1)])):((0,t.uX)(),(0,t.CE)("div",d,r))}var g=l(144),p=l(1387),o=l(4373),b={name:"BadgesDetailsView",setup(){const e=(0,p.lq)(),a=(0,g.KR)(e.params.id),l=(0,g.KR)(null),n=(0,g.KR)(null),s=async()=>{try{const e=await o.A.get("http://localhost/myapp/src/db/api/BadgeDetails.php",{params:{id:a.value}});l.value=e.data}catch(e){n.value=e.message,console.log(n.value)}};return(0,t.sV)(s),{badge:l,id:a}}},h=l(1241);const v=(0,h.A)(b,[["render",c]]);var k=v}}]);
//# sourceMappingURL=214.1e58f31c.js.map