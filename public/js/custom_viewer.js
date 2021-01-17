class Viewer{


    constructor(parent) {
        this.state = {

            background: false,
            playbackSpeed: 1.0,
            actionStates: {},
            wireframe: false,
            skeleton: false,
            grid: false,

            // Lights
            addLights: true,
            exposure: 1.0,
            textureEncoding: 'sRGB',
            ambientIntensity: 0.3,
            ambientColor: 0xFFFFFF,
            directIntensity: 0.8 * Math.PI, // TODO(#116)
            directColor: 0xFFFFFF,
            bgColor1: '#ffffff',
            bgColor2: '#353535'
        };

        this.animations_present=false;
        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);

        this.parent=parent;

        this.renderer = new THREE.WebGLRenderer();
        this.clock =new THREE.Clock();

        window.addEventListener("resize",this.changeDimensions.bind(this));

        this.renderer.physicallyCorrectLights=true;
        this.renderer.outputEncoding=THREE.sRGBEncoding;
        this.renderer.setClearColor( 0xcccccc );
        this.renderer.setPixelRatio( window.devicePixelRatio );
        this.renderer.setSize(parent.clientWidth, parent.clientHeight);

        parent.appendChild(this.renderer.domElement);

        this.controls= new THREE.OrbitControls(this.camera,this.renderer.domElement);
        this.controls.autoRotate = false;
        this.controls.autoRotateSpeed = -10;
        this.controls.screenSpacePanning = true;
        this.controls.addEventListener("change", ()=>this.renderer.render(this.scene, this.camera));

        this.camera.position.z = 10;



    }
    changeDimensions() {
        console.log(this.parent.offsetWidth);
        console.log(this.parent.clientWidth);

        this.renderer.setSize(this.parent.clientWidth, this.parent.clientHeight);
        this.camera.aspect=window.innerWidth / window.innerHeight;
    }

     addLights() {
        this.renderer.toneMappingExposure = this.state.exposure;
        const hemiLight = new THREE.HemisphereLight();
        hemiLight.name = 'hemi_light';
        this.scene.add(hemiLight);
        const light1  = new THREE.AmbientLight(this.state.ambientColor, this.state.ambientIntensity);
        light1.name = 'ambient_light';
        this.scene.add( light1 );

        const light2  = new THREE.DirectionalLight(this.state.directColor, this.state.directIntensity);
        light2.position.set(0.5, 0, 0.866); // ~60º
        light2.name = 'main_light';
        this.scene.add( light2 );

    }

    setContent(gltf) {
        while(this.scene.children.length > 0){
            this.scene.remove(this.scene.children[0]);
        }

        while(this.camera.children.length > 0){
            this.camera.remove(this.camera.children[0]);
        }
        var object=gltf.scene;
        object.position.x=0;
        object.position.y=0;
        object.position.z=0;



        const box = new THREE.Box3().setFromObject(object);
        const size = box.getSize(new THREE.Vector3()).length();
        const center = box.getCenter(new THREE.Vector3());
        object.position.x += (object.position.x - center.x);
        object.position.y += (object.position.y - center.y);
        object.position.z += (object.position.z - center.z);


        this.controls.maxDistance = size * 10;
        this.camera.near = size / 100;
        this.camera.far = size * 100;
        this.camera.updateProjectionMatrix();
        console.log(object);
        console.log(this.camera);

        if(gltf.animations.length>0){
            this.animations_present=true;
        }

        if (this.animations_present) {
            this.mixer = new THREE.AnimationMixer(object);
            console.log(gltf);
            for (let i = 0; i < gltf.animations.length; i++) {
                var action = this.mixer.clipAction(gltf.animations[i]);
                action.play();

            }
        }
        this.scene.add(object);
        this.animate();

        this.addLights();
    }

    updateEncoding(){
        const encoding = THREE.LinearEncoding;
        traverseMaterials(this.content, (material) => {
            if (material.map) material.map.encoding = encoding;
            if (material.emissiveMap) material.emissiveMap.encoding = encoding;
            if (material.map || material.emissiveMap) material.needsUpdate = true;
        });
    }

    traverseMaterials (object, callback) {
        object.traverse((node) => {
            if (!node.isMesh) return;
            const materials = Array.isArray(node.material)
                ? node.material
                : [node.material];
            materials.forEach(callback);
        });
    }

    getModel(model_url){
        return new Promise((resolve, reject) => {
            const manager =new THREE.LoadingManager();
            const loader = new THREE.GLTFLoader(manager)
                .setDRACOLoader( new THREE.DRACOLoader( manager ).setDecoderPath( '/js/three/draco/' ) );
            console.log(model_url);

            loader.load(model_url, function (gltf) {

                resolve(gltf);


            }, undefined, reject);

            });



    }

    animate(){
        requestAnimationFrame(this.animate.bind(this));
        if (this.animations_present) {
            let mixerUpdateDelta = this.clock.getDelta();
            this.mixer.update(mixerUpdateDelta);
        }

        this.renderer.render(this.scene, this.camera);
    }
}


