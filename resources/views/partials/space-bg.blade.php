{{-- resources/views/partials/space-bg.blade.php --}}
{{-- Usage: put @include('partials.space-bg') right after <div class="site-shell" id="top"> --}}

<canvas id="spaceBg" aria-hidden="true"></canvas>

<style>
    /* Replace the old flat background layers with the live space scene */
    .ambient, .grid-background, .orb, #particleCanvas { display: none !important; }

    #spaceBg {
        position: fixed;
        inset: 0;
        z-index: -1;
        width: 100%;
        height: 100%;
        display: block;
        pointer-events: none;      /* clicks are read from the window, so the page stays usable */
        background: #02030a;
    }

    /* Let the scene show through the sections (kept readable) */
    .features-section { background: linear-gradient(180deg, rgba(8,13,23,.28), rgba(9,17,29,.5)); }
    .stats-section    { background: rgba(8,13,23,.26); border-top-color: rgba(255,255,255,.06); }
    .footer           { background: rgba(5,9,16,.72); backdrop-filter: blur(10px); }
    .intel-overview   { background: linear-gradient(145deg, rgba(16,25,41,.7), rgba(8,13,24,.72)); backdrop-filter: blur(8px); }
    .dashboard        { background: linear-gradient(145deg, rgba(18,29,47,.84), rgba(9,15,27,.88)); }
    .feature-card     { background: linear-gradient(145deg, rgba(20,32,50,.6), rgba(12,19,32,.66)); backdrop-filter: blur(6px); }
    .stats-card, .frequency-panel { backdrop-filter: blur(6px); }
    .hero-copy h1, .hero-description, .section-head h2, .section-head p { text-shadow: 0 2px 18px rgba(0,0,0,.65); }
</style>

<script>
(() => {
    const cv = document.getElementById('spaceBg');
    const g = cv.getContext('2d');
    const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
    const rnd = Math.random;
    let W, H, cx, cy, TILT, U, bg, stars = [], dust = [];
    let t = 0, last = 0, flash = 0, modeI = 0, drag = null, running = true;
    const fx = [], comets = [], shoot = [], rip = [];

    const P = [
        { a:.20, e:.21, T:4.1, rot:5,  r:3.2, c:['#c9b8a6','#7a6e64'], w:.2 },
        { a:.30, e:.02, T:6.5, rot:-3, r:5.4, c:['#f3d79b','#c18d45'], w:1 },
        { a:.41, e:.04, T:9,   rot:9,  r:5.8, c:['#6fb8ff','#1d4fa8'], w:2.1, moon:1, earth:1 },
        { a:.52, e:.09, T:13,  rot:8,  r:4.4, c:['#ff9a6b','#a3401f'], w:3.3 },
        { a:.70, e:.05, T:24,  rot:14, r:12,  c:['#e8c9a0','#a8743f'], w:4, bands:1 },
        { a:.86, e:.06, T:34,  rot:12, r:10,  c:['#f1deaa','#b99a5b'], w:5, ring:1, bands:1 },
        { a:.98, e:.05, T:48,  rot:-8, r:7,   c:['#a8f0f0','#4aa9b5'], w:.8, ring:.5 },
        { a:1.09,e:.01, T:62,  rot:8,  r:6.8, c:['#6f8cff','#2a3fb5'], w:2.6 }
    ];
    P.forEach(p => { p.th = p.w; p.spin = rnd()*6.28; p.ox = p.oy = p.vx = p.vy = 0; p.trail = []; });

    function resize() {
        const dpr = Math.min(devicePixelRatio || 1, 2);
        W = innerWidth; H = innerHeight;
        cv.width = W*dpr; cv.height = H*dpr;
        g.setTransform(dpr,0,0,dpr,0,0);
        cx = W/2; cy = H/2;
        const portrait = H > W;
        TILT = portrait ? 1 : Math.min(.62, Math.max(.4, H/W*.8));
        U = Math.max(.7, Math.min(1.5, Math.min(W, H)/720));
        makeBG();
    }
    const sc = () => Math.min(W/2/1.18, H/2/(TILT*1.18));

    function makeBG() {
        bg = document.createElement('canvas'); bg.width = W; bg.height = H;
        const b = bg.getContext('2d');
        const gr = b.createLinearGradient(0,0,W*.4,H);
        gr.addColorStop(0,'#0a1236'); gr.addColorStop(.5,'#1b0d3a'); gr.addColorStop(1,'#010105');
        b.fillStyle = gr; b.fillRect(0,0,W,H);
        const cols = ['120,80,255','60,120,255','255,80,170','80,200,255'];
        for (let i=0;i<14;i++) {
            const x=rnd()*W, y=rnd()*H, r=(.15+rnd()*.3)*Math.max(W,H), c=cols[i%4];
            const rg=b.createRadialGradient(x,y,0,x,y,r);
            rg.addColorStop(0,`rgba(${c},.12)`); rg.addColorStop(1,`rgba(${c},0)`);
            b.fillStyle=rg; b.fillRect(0,0,W,H);
        }
        const n = Math.min(900, Math.floor(W*H/3200));
        stars = Array.from({length:n}, () => { const x=rnd()*W, y=rnd()*H;
            return { hx:x, hy:y, x, y, vx:0, vy:0, r:rnd()*1.3+.3, tw:rnd()*6.28, ts:.5+rnd()*2, d:.3+rnd()*.7 }; });
        dust = Array.from({length: W<600?30:60}, () => ({ a:rnd()*6.28, r:rnd()*1.2, s:rnd()*.02+.005, sz:rnd()*2+.5, al:rnd()*.25+.05 }));
    }

    const planetXY = p => { const A=sc()*p.a, c=A*p.e; return [cx+Math.cos(p.th)*A-c, cy+Math.sin(p.th)*A*(1-p.e)*TILT]; };

    function shock(x,y,k) {
        for (const s of stars) { const dx=s.x-x, dy=s.y-y, d=Math.hypot(dx,dy)+8, f=Math.min(1,160/d)*k*(1.5+s.d*2.5); s.vx+=dx/d*f*14; s.vy+=dy/d*f*14; }
        for (const p of P) { const q=planetXY(p), dx=q[0]-x, dy=q[1]-y, d=Math.hypot(dx,dy)+30, f=Math.min(1,260/d)*k; p.vx+=dx/d*f*40; p.vy+=dy/d*f*40; }
    }
    function spawnComet(x,y,vx,vy) { comets.push({x,y,vx,vy,t:[],l:1}); }
    function burst(x,y) {
        const kind = modeI++ % 4;
        rip.push({x,y,r:0,v:420,a:1,w:kind===2?2:1});
        shock(x,y,kind===2?1.8:1);
        if (kind===0 || kind===2) {
            const n = kind===2 ? 150 : 90;
            for (let i=0;i<n;i++) { const a=rnd()*6.28, s=(kind===2?260:150)*(.2+rnd()*rnd()*1.3);
                fx.push({x,y,vx:Math.cos(a)*s,vy:Math.sin(a)*s,l:1,dl:.4+rnd()*.8,sz:rnd()*2.4+.6,h:kind===2?20+rnd()*30:190+rnd()*110}); }
        }
        if (kind===2) flash = 1;
        if (kind===1 || kind===3) { const a=rnd()*6.28, s=260+rnd()*160; spawnComet(x,y,Math.cos(a)*s,Math.sin(a)*s); }
    }

    // Input: read from the window so the background reacts through the page,
    // but never hijack links, buttons or form controls.
    const interactive = 'a,button,input,select,textarea,label,summary,[role="button"],.nav-menu,.menu-toggle';
    addEventListener('pointerdown', e => {
        if (e.target.closest(interactive)) return;
        drag = { x:e.clientX, y:e.clientY, t:performance.now(), id:e.pointerId };
    }, { passive:true });
    addEventListener('pointercancel', () => { drag = null; }, { passive:true });   // fired when a touch becomes a scroll
    addEventListener('pointerup', e => {
        if (!drag) return;
        const dx=e.clientX-drag.x, dy=e.clientY-drag.y, d=Math.hypot(dx,dy);
        const sel = getSelection && String(getSelection());
        if (d > 40 && e.pointerType === 'mouse' && !sel) {
            const dt = Math.max(.05,(performance.now()-drag.t)/1000), v = Math.min(900, d/dt*.6);
            spawnComet(drag.x, drag.y, dx/d*v, dy/d*v);
            rip.push({x:drag.x,y:drag.y,r:0,v:300,a:.7,w:1});
        } else if (d < 12) burst(e.clientX, e.clientY);
        drag = null;
    }, { passive:true });
    document.addEventListener('visibilitychange', () => { running = !document.hidden; if (running) { last = 0; requestAnimationFrame(frame); } });
    let rt; addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(resize, 120); }, { passive:true });

    function drawSun() {
        const R0 = Math.min(W,H)*.045*(1+Math.sin(t*1.7)*.04+Math.sin(t*3.1)*.02);
        g.save(); g.globalCompositeOperation='lighter';
        let rg = g.createRadialGradient(cx,cy,R0*.5,cx,cy,R0*7);
        rg.addColorStop(0,'rgba(255,190,80,.55)'); rg.addColorStop(.25,'rgba(255,120,30,.18)'); rg.addColorStop(1,'rgba(255,80,0,0)');
        g.fillStyle=rg; g.fillRect(cx-R0*7,cy-R0*7,R0*14,R0*14);
        g.translate(cx,cy);
        for (let i=0;i<18;i++) { const a=i/18*6.28+t*.05, L=R0*(3.2+Math.sin(t*1.3+i*2.1)*1.2+(i%3)*.8);
            const lg=g.createLinearGradient(0,0,Math.cos(a)*L,Math.sin(a)*L);
            lg.addColorStop(0,'rgba(255,200,110,.22)'); lg.addColorStop(1,'rgba(255,140,40,0)');
            g.strokeStyle=lg; g.lineWidth=R0*.12; g.beginPath(); g.moveTo(Math.cos(a)*R0,Math.sin(a)*R0); g.lineTo(Math.cos(a)*L,Math.sin(a)*L); g.stroke(); }
        for (let i=0;i<7;i++) { const a=i/7*6.28+t*.12+Math.sin(t*.6+i)*.4, h=R0*(.7+.5*Math.sin(t*1.4+i*1.9)), b=.3;
            g.fillStyle='rgba(255,130,40,.2)'; g.beginPath();
            g.moveTo(Math.cos(a-b)*R0*.95,Math.sin(a-b)*R0*.95);
            g.quadraticCurveTo(Math.cos(a-b*.2)*(R0+h*1.6),Math.sin(a-b*.2)*(R0+h*1.6),Math.cos(a)*(R0+h),Math.sin(a)*(R0+h));
            g.quadraticCurveTo(Math.cos(a+b*.2)*(R0+h*1.6),Math.sin(a+b*.2)*(R0+h*1.6),Math.cos(a+b)*R0*.95,Math.sin(a+b)*R0*.95);
            g.fill(); }
        g.restore();
        g.save(); g.translate(cx,cy);
        rg=g.createRadialGradient(-R0*.2,-R0*.2,R0*.1,0,0,R0);
        rg.addColorStop(0,'#fff6c8'); rg.addColorStop(.5,'#ffc23a'); rg.addColorStop(1,'#ff7a12');
        g.fillStyle=rg; g.beginPath(); g.arc(0,0,R0,0,6.28); g.fill(); g.clip();
        g.globalCompositeOperation='lighter';
        for (let i=0;i<9;i++) { const a=i*1.9+t*.15, d=R0*(.2+(i%4)*.18); g.fillStyle='rgba(255,230,140,.1)'; g.beginPath(); g.arc(Math.cos(a)*d,Math.sin(a*1.3)*d,R0*.25,0,6.28); g.fill(); }
        g.restore();
        return R0;
    }
    function shade(p,x,y,r) {
        const lx=cx-x, ly=cy-y, L=Math.hypot(lx,ly)||1, ux=lx/L, uy=ly/L;
        let rg=g.createRadialGradient(x+ux*r*.45,y+uy*r*.45,r*.1,x,y,r*1.05);
        rg.addColorStop(0,p.c[0]); rg.addColorStop(1,p.c[1]);
        g.save(); g.beginPath(); g.arc(x,y,r,0,6.28); g.fillStyle=rg; g.fill(); g.clip();
        if (p.bands) for (let i=-4;i<=4;i++) { g.fillStyle=`rgba(90,50,20,${.1+(i&1)*.08})`; g.fillRect(x-r,y+i*r*.2+Math.sin(p.spin)*r*.04,r*2,r*.09); }
        else for (let i=0;i<3;i++) { const a=p.spin+i*2.1; g.fillStyle='rgba(255,255,255,.12)'; g.beginPath(); g.arc(x+Math.cos(a)*r*.5,y+Math.sin(i*3+a*.3)*r*.45,r*.28,0,6.28); g.fill(); }
        if (p.earth) { g.fillStyle='rgba(70,160,80,.55)'; for (let i=0;i<3;i++) { const a=p.spin*1.2+i*2.3; if (Math.cos(a)>-.3) { g.beginPath(); g.ellipse(x+Math.sin(a)*r*.6,y+(i-1)*r*.35,r*.28,r*.2,0,0,6.28); g.fill(); } } }
        const sg=g.createRadialGradient(x+ux*r*.6,y+uy*r*.6,r*.5,x-ux*r*.4,y-uy*r*.4,r*1.5);
        sg.addColorStop(0,'rgba(0,0,10,0)'); sg.addColorStop(1,'rgba(0,0,12,.85)');
        g.fillStyle=sg; g.fillRect(x-r,y-r,r*2,r*2); g.restore();
        g.save(); g.globalCompositeOperation='lighter';
        const ag=g.createRadialGradient(x,y,r*.9,x,y,r*1.7); ag.addColorStop(0,p.c[0]+'55'); ag.addColorStop(1,p.c[0]+'00');
        g.fillStyle=ag; g.beginPath(); g.arc(x,y,r*1.7,0,6.28); g.fill(); g.restore();
    }
    function ring(p,x,y,r,front) {
        g.save(); g.translate(x,y); g.rotate(-.35); g.scale(1,.32);
        g.beginPath(); front ? g.rect(-r*4,0,r*8,r*4) : g.rect(-r*4,-r*4,r*8,r*4); g.clip();
        const bands = p.ring===1 ? [[1.5,1.7,.55],[1.75,1.95,.4],[2,2.35,.5],[2.4,2.55,.25]] : [[1.7,1.76,.35],[1.9,1.95,.3]];
        for (const b of bands) { g.strokeStyle=`rgba(236,214,160,${b[2]})`; g.lineWidth=(b[1]-b[0])*r; g.beginPath(); g.arc(0,0,(b[0]+b[1])/2*r,0,6.28); g.stroke(); }
        g.restore();
    }

    function frame(now) {
        if (!running) return;
        const dt = Math.min(.05, ((now - (last || now)) / 1000)) * (reduce ? .4 : 1); last = now; t += dt;
        g.drawImage(bg,0,0,W,H);

        g.save(); g.globalCompositeOperation='lighter';
        for (const d of dust) { d.a += d.s*dt; const x=cx+Math.cos(d.a)*d.r*W*.6, y=cy+Math.sin(d.a)*d.r*H*.5, s=d.sz*30;
            const rg=g.createRadialGradient(x,y,0,x,y,s); rg.addColorStop(0,`rgba(150,130,255,${d.al*.18})`); rg.addColorStop(1,'rgba(150,130,255,0)');
            g.fillStyle=rg; g.fillRect(x-s,y-s,s*2,s*2); }
        g.restore();

        const damp1 = Math.pow(.02,dt);
        for (const s of stars) {
            s.vx += (s.hx-s.x)*6*dt; s.vy += (s.hy-s.y)*6*dt; s.vx *= damp1; s.vy *= damp1; s.x += s.vx*dt; s.y += s.vy*dt;
            const a = .35+.65*(.5+.5*Math.sin(t*s.ts+s.tw));
            g.fillStyle=`rgba(${200+s.d*55|0},${210+s.d*45|0},255,${a*s.d})`; g.fillRect(s.x,s.y,s.r,s.r);
        }

        if (!reduce && rnd() < dt*.25) shoot.push({x:rnd()*W,y:rnd()*H*.5,vx:(rnd()<.5?-1:1)*(500+rnd()*400),vy:200+rnd()*250,l:1});
        for (let i=shoot.length-1;i>=0;i--) { const s=shoot[i]; s.x+=s.vx*dt; s.y+=s.vy*dt; s.l-=dt*1.1;
            if (s.l<=0) { shoot.splice(i,1); continue; }
            const lg=g.createLinearGradient(s.x,s.y,s.x-s.vx*.12,s.y-s.vy*.12); lg.addColorStop(0,`rgba(255,255,255,${s.l})`); lg.addColorStop(1,'rgba(255,255,255,0)');
            g.strokeStyle=lg; g.lineWidth=1.6; g.beginPath(); g.moveTo(s.x,s.y); g.lineTo(s.x-s.vx*.12,s.y-s.vy*.12); g.stroke(); }

        g.save(); g.globalCompositeOperation='lighter';
        for (let i=rip.length-1;i>=0;i--) { const r=rip[i]; r.r+=r.v*dt; r.a-=dt*.7;
            if (r.a<=0) { rip.splice(i,1); continue; }
            for (let k=0;k<3;k++) { g.strokeStyle=`rgba(${140+k*40},${160+k*30},255,${r.a*(.5-k*.14)})`; g.lineWidth=(3-k)*r.w; g.beginPath(); g.arc(r.x,r.y,Math.max(0,r.r-k*14),0,6.28); g.stroke(); } }
        g.restore();

        g.lineWidth=1;
        for (const p of P) { g.strokeStyle='rgba(150,170,255,.1)'; g.beginPath(); const A=sc()*p.a, c=A*p.e; g.ellipse(cx-c,cy,A,A*(1-p.e)*TILT,0,0,6.28); g.stroke(); }

        const R0 = drawSun();

        const damp2 = Math.pow(.05,dt);
        const items = P.map(p => {
            const rr = 1-p.e*Math.cos(p.th); p.th += (6.283/p.T)/(rr*rr)*Math.sqrt(1-p.e*p.e)*dt; p.spin += p.rot*dt*.4;
            p.ox += p.vx*dt; p.oy += p.vy*dt; p.vx += -p.ox*5*dt; p.vy += -p.oy*5*dt; p.vx *= damp2; p.vy *= damp2;
            const q = planetXY(p);
            for (const c of comets) { const dx=c.x-q[0]-p.ox, dy=c.y-q[1]-p.oy, d=Math.hypot(dx,dy); if (d<160 && d>1) { p.vx+=dx/d*(160-d)*.6*dt; p.vy+=dy/d*(160-d)*.6*dt; } }
            return { p, x:q[0]+p.ox, y:q[1]+p.oy };
        }).sort((a,b)=>a.y-b.y);

        for (const it of items) {
            const p=it.p, r=p.r*U;
            p.trail.push([it.x,it.y]); if (p.trail.length>26) p.trail.shift();
            g.strokeStyle=p.c[0]+'30'; g.lineWidth=1.2; g.beginPath(); p.trail.forEach((q,i)=>i?g.lineTo(q[0],q[1]):g.moveTo(q[0],q[1])); g.stroke();
            if (p.ring) ring(p,it.x,it.y,r,0);
            shade(p,it.x,it.y,r);
            if (p.ring) ring(p,it.x,it.y,r,1);
            if (p.moon) { const a=t*2.4, mx=it.x+Math.cos(a)*r*2.4, my=it.y+Math.sin(a)*r*.9; g.fillStyle='#cfd2dc'; g.beginPath(); g.arc(mx,my,r*.28,0,6.28); g.fill(); }
        }

        g.save(); g.globalCompositeOperation='lighter';
        for (let i=comets.length-1;i>=0;i--) { const c=comets[i];
            const dx=cx-c.x, dy=cy-c.y, d=Math.max(40,Math.hypot(dx,dy)), gr=90000/(d*d);
            c.vx+=dx/d*gr*dt*60; c.vy+=dy/d*gr*dt*60; c.x+=c.vx*dt; c.y+=c.vy*dt; c.l-=dt*.12;
            c.t.push([c.x,c.y]); if (c.t.length>46) c.t.shift();
            if (c.l<=0 || d<R0 || c.x<-300 || c.x>W+300 || c.y<-300 || c.y>H+300) { comets.splice(i,1); continue; }
            c.t.forEach((q,j)=>{ const f=j/c.t.length; g.fillStyle=`rgba(150,210,255,${f*.35*c.l})`; g.beginPath(); g.arc(q[0],q[1],f*3.2,0,6.28); g.fill(); });
            const ta=Math.atan2(c.y-cy,c.x-cx), tl=70+Math.min(60,3e4/d);
            const lg=g.createLinearGradient(c.x,c.y,c.x+Math.cos(ta)*tl,c.y+Math.sin(ta)*tl); lg.addColorStop(0,'rgba(180,230,255,.5)'); lg.addColorStop(1,'rgba(180,230,255,0)');
            g.strokeStyle=lg; g.lineWidth=5; g.beginPath(); g.moveTo(c.x,c.y); g.lineTo(c.x+Math.cos(ta)*tl,c.y+Math.sin(ta)*tl); g.stroke();
            const hg=g.createRadialGradient(c.x,c.y,0,c.x,c.y,14); hg.addColorStop(0,'rgba(255,255,255,1)'); hg.addColorStop(1,'rgba(150,210,255,0)');
            g.fillStyle=hg; g.beginPath(); g.arc(c.x,c.y,14,0,6.28); g.fill(); }
        for (let i=fx.length-1;i>=0;i--) { const f=fx[i]; f.l-=dt/f.dl;
            if (f.l<=0) { fx.splice(i,1); continue; }
            const dx=cx-f.x, dy=cy-f.y, d=Math.max(60,Math.hypot(dx,dy));
            f.vx+=dx/d*60000/(d*d)*dt*10; f.vy+=dy/d*60000/(d*d)*dt*10; f.vx*=Math.pow(.35,dt); f.vy*=Math.pow(.35,dt); f.x+=f.vx*dt; f.y+=f.vy*dt;
            g.fillStyle=`hsla(${f.h},100%,${65+f.l*25}%,${f.l*.9})`; g.beginPath(); g.arc(f.x,f.y,f.sz*f.l+.3,0,6.28); g.fill(); }
        g.restore();

        if (flash>0) { g.fillStyle=`rgba(255,240,220,${flash*.4})`; g.fillRect(0,0,W,H); flash -= dt*1.8; }
        requestAnimationFrame(frame);
    }

    resize();
    requestAnimationFrame(frame);
})();
</script>
