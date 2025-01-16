<?php
if (!defined('__TYPECHO_ROOT_DIR__') || empty($_SERVER['HTTP_HOST'])) {
    http_response_code(404);
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>穿越太空的火箭 - <?= Helper::options()->title ?></title>
    <meta http-equiv="refresh" content="3;URL=<?= htmlentities($location) ?>">
    <style>
        :root {
            --ship-size: 10vmin;
            --sky-color: #1C1740;
            --ship-color: #F9E2FE;
            --ship-cap-color: crimson;
            --ship-wing-color: #4C3198;
            --ship-window-trim-color: #4C3198;
            --ship-booster-color: #C38382;
            --star-color: white;
            --stars-sm-speed: 5s;
            --stars-md-speed: 2s;
            --stars-lg-speed: 1s
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%
        }

        body {
            display: flex;
            position: relative;
            overflow: hidden;
            background-color: var(--sky-color)
        }

        .ship,
        .star-field {
            position: absolute;
            top: 50%;
            left: 50%;
            will-change: transform;
            transition: transform 0.4s ease;
            transform: translate(-50%, -50%)
        }

        .star-field {
            width: 200%;
            height: 200%;
            transition: transform 1s ease-out
        }

        .hover-area {
            flex-grow: 1;
            z-index: 3
        }

        .hover-area:nth-child(1):hover~.ship,
        .hover-area:nth-child(1):hover~.star-field {
            transform: translate(-50%, -50%) rotate(-90deg)
        }

        .hover-area:nth-child(2):hover~.ship,
        .hover-area:nth-child(2):hover~.star-field {
            transform: translate(-50%, -50%) rotate(-80deg)
        }

        .hover-area:nth-child(3):hover~.ship,
        .hover-area:nth-child(3):hover~.star-field {
            transform: translate(-50%, -50%) rotate(-70deg)
        }

        .hover-area:nth-child(4):hover~.ship,
        .hover-area:nth-child(4):hover~.star-field {
            transform: translate(-50%, -50%) rotate(-60deg)
        }

        .hover-area:nth-child(5):hover~.ship,
        .hover-area:nth-child(5):hover~.star-field {
            transform: translate(-50%, -50%) rotate(-50deg)
        }

        .hover-area:nth-child(6):hover~.ship,
        .hover-area:nth-child(6):hover~.star-field {
            transform: translate(-50%, -50%) rotate(-40deg)
        }

        .hover-area:nth-child(7):hover~.ship,
        .hover-area:nth-child(7):hover~.star-field {
            transform: translate(-50%, -50%) rotate(-30deg)
        }

        .hover-area:nth-child(8):hover~.ship,
        .hover-area:nth-child(8):hover~.star-field {
            transform: translate(-50%, -50%) rotate(-20deg)
        }

        .hover-area:nth-child(9):hover~.ship,
        .hover-area:nth-child(9):hover~.star-field {
            transform: translate(-50%, -50%) rotate(-10deg)
        }

        .hover-area:nth-child(10):hover~.ship,
        .hover-area:nth-child(10):hover~.star-field {
            transform: translate(-50%, -50%) rotate(0deg)
        }

        .hover-area:nth-child(11):hover~.ship,
        .hover-area:nth-child(11):hover~.star-field {
            transform: translate(-50%, -50%) rotate(0deg)
        }

        .hover-area:nth-child(12):hover~.ship,
        .hover-area:nth-child(12):hover~.star-field {
            transform: translate(-50%, -50%) rotate(10deg)
        }

        .hover-area:nth-child(13):hover~.ship,
        .hover-area:nth-child(13):hover~.star-field {
            transform: translate(-50%, -50%) rotate(20deg)
        }

        .hover-area:nth-child(14):hover~.ship,
        .hover-area:nth-child(14):hover~.star-field {
            transform: translate(-50%, -50%) rotate(30deg)
        }

        .hover-area:nth-child(15):hover~.ship,
        .hover-area:nth-child(15):hover~.star-field {
            transform: translate(-50%, -50%) rotate(40deg)
        }

        .hover-area:nth-child(16):hover~.ship,
        .hover-area:nth-child(16):hover~.star-field {
            transform: translate(-50%, -50%) rotate(50deg)
        }

        .hover-area:nth-child(17):hover~.ship,
        .hover-area:nth-child(17):hover~.star-field {
            transform: translate(-50%, -50%) rotate(60deg)
        }

        .hover-area:nth-child(18):hover~.ship,
        .hover-area:nth-child(18):hover~.star-field {
            transform: translate(-50%, -50%) rotate(70deg)
        }

        .hover-area:nth-child(19):hover~.ship,
        .hover-area:nth-child(19):hover~.star-field {
            transform: translate(-50%, -50%) rotate(80deg)
        }

        .hover-area:nth-child(20):hover~.ship,
        .hover-area:nth-child(20):hover~.star-field {
            transform: translate(-50%, -50%) rotate(90deg)
        }

        .hover-area:active~.star-field .stars-sm:before,
        .hover-area:active~.star-field .stars-sm:after {
            animation-duration: calc(var(--stars-sm-speed) / 2)
        }

        .hover-area:active~.star-field .stars-sm:after {
            animation-delay: calc(var(--stars-sm-speed) / -4)
        }

        .hover-area:active~.star-field .stars-md:before,
        .hover-area:active~.star-field .stars-md:after {
            animation-duration: calc(var(--stars-md-speed) / 2)
        }

        .hover-area:active~.star-field .stars-md:after {
            animation-delay: calc(var(--stars-md-speed) / -4)
        }

        .hover-area:active~.star-field .stars-lg:before,
        .hover-area:active~.star-field .stars-lg:after {
            animation-duration: calc(var(--stars-lg-speed) / 2)
        }

        .hover-area:active~.star-field .stars-lg:after {
            animation-delay: calc(var(--stars-lg-speed) / -4)
        }

        .hover-area:active~.ship .wrapper {
            animation: speed-up-ship 80ms linear infinite alternate
        }

        .hover-area:active~.ship .exhaust {
            animation: speed-up-exhaust 80ms linear infinite alternate
        }

        .ship .wrapper {
            display: flex
        }

        .ship .body {
            position: relative;
            background-color: var(--ship-color);
            border-radius: 0 0 50% 50%/76% 76% 15% 15%
        }

        .ship .body:before {
            content: "";
            position: absolute;
            border-radius: 50% 50% 50% 50%/76% 76% 25% 25%
        }

        .ship .main {
            width: var(--ship-size);
            height: calc(var(--ship-size) * 1.5);
            box-shadow: inset rgba(0, 0, 0, 0.15) -0.5vmin 0 2vmin 0
        }

        .ship .main:before {
            bottom: 80%;
            width: 100%;
            height: 75%;
            background-color: inherit;
            box-shadow: inset rgba(0, 0, 0, 0.15) -0.5vmin 1vmin 1vmin 0
        }

        .ship .main:after {
            content: "";
            position: absolute;
            bottom: 75%;
            left: 0;
            right: 0;
            margin: auto;
            border: calc(var(--ship-size) / 15) solid var(--ship-window-trim-color);
            width: calc(var(--ship-size) / 1.8);
            height: calc(var(--ship-size) / 1.8);
            box-shadow: inset rgba(0, 0, 0, 0.075) -2vmin -2vmin 0 0, inset rgba(0, 0, 0, 0.1) -1vmin -1.5vmin 0 0;
            border-radius: 100%
        }

        .ship .side {
            width: calc(var(--ship-size) / 3);
            height: var(--ship-size);
            box-shadow: inset rgba(0, 0, 0, 0.1) -0.5vmin 0 1vmin 0, inset rgba(0, 0, 0, 0.1) 0.5vmin 0 1vmin 0
        }

        .ship .side:before {
            bottom: 90%;
            width: 100%;
            height: 35%;
            background-color: var(--ship-cap-color);
            box-shadow: inset rgba(0, 0, 0, 0.2) -0.5vmin 1vmin 1vmin 0, inset rgba(255, 255, 255, 0.2) 0.5vmin 1vmin 1vmin 0
        }

        .ship .side.left {
            left: 1px
        }

        .ship .side.right {
            right: 1px
        }

        .ship .wing {
            position: absolute;
            bottom: 2vmin;
            background-color: var(--ship-wing-color);
            width: calc(var(--ship-size) / 2);
            height: calc(var(--ship-size) / 1.5);
            z-index: 1;
            box-shadow: inset rgba(0, 0, 0, 0.1) -0.5vmin 1vmin 1vmin 0, inset rgba(255, 255, 255, 0.1) 0.5vmin 1vmin 1vmin 0
        }

        .ship .wing.left {
            right: 100%;
            border-radius: 100% 0 10% 10%
        }

        .ship .wing.right {
            left: 100%;
            border-radius: 0 100% 10% 10%
        }

        .ship .booster {
            position: absolute;
            top: 80%;
            left: 0;
            right: 0;
            margin: auto;
            width: calc(var(--ship-size) / 1.2);
            height: calc(var(--ship-size) / 2.5);
            background-color: var(--ship-booster-color);
            border-radius: 0 0 50% 50%/76% 76% 35% 35%;
            z-index: -1;
            box-shadow: inset rgba(0, 0, 0, 0.3) -0.5vmin 1vmin 1vmin 0, inset rgba(255, 255, 255, 0.3) 0.5vmin 1vmin 1vmin 0, black 0 0 2vmin
        }

        .ship .exhaust {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin: auto;
            width: calc(var(--ship-size) / 1.4);
            height: 80%;
            border-radius: 0 0 100% 100%;
            background-image: linear-gradient(to bottom, yellow, transparent 70%);
            z-index: -2;
            transform-origin: 50% 0;
            animation: exhaust 0.1s linear alternate infinite
        }

        .stars {
            position: absolute;
            top: 0;
            left: 0
        }

        .stars:before,
        .stars:after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            animation: stars linear infinite;
            transform: translateY(-100vh)
        }

        .stars-sm {
            width: 1px;
            height: 1px
        }

        .stars-sm:before,
        .stars-sm:after {
            box-shadow: 80vw 36vh var(--star-color), 138vw 73vh var(--star-color), 129vw 105vh var(--star-color), 13vw 184vh var(--star-color), 141vw 124vh var(--star-color), 3vw 153vh var(--star-color), 63vw 177vh var(--star-color), 90vw 153vh var(--star-color), 47vw 113vh var(--star-color), 41vw 181vh var(--star-color), 123vw 46vh var(--star-color), 135vw 76vh var(--star-color), 182vw 138vh var(--star-color), 118vw 13vh var(--star-color), 51vw 182vh var(--star-color), 128vw 142vh var(--star-color), 24vw 88vh var(--star-color), 139vw 49vh var(--star-color), 169vw 134vh var(--star-color), 23vw 132vh var(--star-color), 118vw 69vh var(--star-color), 40vw 132vh var(--star-color), 139vw 77vh var(--star-color), 66vw 23vh var(--star-color), 18vw 51vh var(--star-color), 181vw 160vh var(--star-color), 154vw 145vh var(--star-color), 51vw 171vh var(--star-color), 65vw 132vh var(--star-color), 155vw 80vh var(--star-color), 79vw 46vh var(--star-color), 188vw 35vh var(--star-color), 169vw 144vh var(--star-color), 134vw 173vh var(--star-color), 81vw 126vh var(--star-color), 114vw 65vh var(--star-color), 28vw 126vh var(--star-color), 18vw 197vh var(--star-color), 90vw 3vh var(--star-color), 107vw 140vh var(--star-color), 62vw 15vh var(--star-color), 149vw 94vh var(--star-color), 192vw 169vh var(--star-color), 105vw 199vh var(--star-color), 174vw 109vh var(--star-color), 30vw 55vh var(--star-color), 123vw 59vh var(--star-color), 115vw 182vh var(--star-color), 117vw 175vh var(--star-color), 180vw 39vh var(--star-color), 10vw 194vh var(--star-color), 13vw 172vh var(--star-color), 4vw 72vh var(--star-color), 12vw 79vh var(--star-color), 140vw 5vh var(--star-color), 45vw 121vh var(--star-color), 173vw 39vh var(--star-color), 157vw 124vh var(--star-color), 132vw 26vh var(--star-color), 139vw 155vh var(--star-color), 121vw 3vh var(--star-color), 182vw 118vh var(--star-color), 59vw 195vh var(--star-color), 84vw 75vh var(--star-color), 9vw 12vh var(--star-color), 136vw 53vh var(--star-color), 109vw 59vh var(--star-color), 151vw 6vh var(--star-color), 173vw 140vh var(--star-color), 67vw 45vh var(--star-color), 32vw 178vh var(--star-color), 161vw 160vh var(--star-color), 181vw 113vh var(--star-color), 34vw 196vh var(--star-color), 172vw 28vh var(--star-color), 65vw 158vh var(--star-color), 139vw 139vh var(--star-color), 81vw 99vh var(--star-color), 27vw 34vh var(--star-color), 39vw 38vh var(--star-color), 178vw 94vh var(--star-color), 129vw 189vh var(--star-color), 130vw 97vh var(--star-color), 53vw 39vh var(--star-color), 177vw 97vh var(--star-color), 151vw 68vh var(--star-color), 188vw 35vh var(--star-color), 129vw 185vh var(--star-color), 8vw 182vh var(--star-color), 72vw 155vh var(--star-color), 36vw 96vh var(--star-color), 158vw 197vh var(--star-color), 86vw 63vh var(--star-color), 137vw 24vh var(--star-color), 55vw 101vh var(--star-color), 200vw 93vh var(--star-color), 15vw 25vh var(--star-color), 44vw 71vh var(--star-color), 27vw 193vh var(--star-color), 94vw 121vh var(--star-color), 97vw 143vh var(--star-color), 66vw 22vh var(--star-color), 163vw 156vh var(--star-color), 78vw 94vh var(--star-color), 71vw 183vh var(--star-color), 11vw 9vh var(--star-color), 152vw 85vh var(--star-color), 117vw 121vh var(--star-color), 88vw 102vh var(--star-color), 26vw 131vh var(--star-color), 184vw 186vh var(--star-color), 160vw 83vh var(--star-color), 167vw 145vh var(--star-color), 129vw 34vh var(--star-color), 165vw 179vh var(--star-color), 29vw 181vh var(--star-color), 32vw 172vh var(--star-color), 188vw 136vh var(--star-color), 64vw 83vh var(--star-color), 1vw 54vh var(--star-color), 48vw 25vh var(--star-color), 191vw 57vh var(--star-color), 47vw 33vh var(--star-color), 161vw 34vh var(--star-color), 165vw 159vh var(--star-color), 3vw 48vh var(--star-color), 137vw 58vh var(--star-color), 156vw 64vh var(--star-color), 134vw 108vh var(--star-color), 56vw 177vh var(--star-color), 74vw 197vh var(--star-color), 14vw 19vh var(--star-color), 76vw 93vh var(--star-color), 133vw 51vh var(--star-color), 122vw 61vh var(--star-color), 39vw 117vh var(--star-color), 68vw 185vh var(--star-color), 51vw 51vh var(--star-color), 46vw 106vh var(--star-color), 97vw 48vh var(--star-color), 23vw 23vh var(--star-color), 110vw 141vh var(--star-color), 146vw 197vh var(--star-color), 101vw 121vh var(--star-color), 124vw 56vh var(--star-color), 138vw 39vh var(--star-color), 157vw 128vh var(--star-color), 104vw 140vh var(--star-color), 114vw 172vh var(--star-color), 173vw 7vh var(--star-color), 13vw 183vh var(--star-color), 132vw 89vh var(--star-color), 28vw 18vh var(--star-color), 18vw 132vh var(--star-color), 42vw 172vh var(--star-color), 64vw 1vh var(--star-color), 88vw 73vh var(--star-color), 99vw 101vh var(--star-color), 123vw 163vh var(--star-color), 102vw 176vh var(--star-color), 188vw 15vh var(--star-color), 54vw 111vh var(--star-color), 91vw 158vh var(--star-color), 24vw 133vh var(--star-color), 117vw 19vh var(--star-color), 155vw 133vh var(--star-color), 72vw 181vh var(--star-color), 151vw 175vh var(--star-color), 62vw 61vh var(--star-color), 57vw 19vh var(--star-color), 39vw 143vh var(--star-color), 163vw 9vh var(--star-color), 60vw 116vh var(--star-color), 49vw 84vh var(--star-color), 63vw 115vh var(--star-color), 198vw 126vh var(--star-color), 31vw 21vh var(--star-color), 174vw 183vh var(--star-color), 185vw 75vh var(--star-color), 115vw 85vh var(--star-color), 106vw 98vh var(--star-color), 67vw 2vh var(--star-color), 162vw 92vh var(--star-color), 44vw 18vh var(--star-color), 13vw 51vh var(--star-color), 69vw 198vh var(--star-color), 40vw 102vh var(--star-color), 190vw 161vh var(--star-color), 3vw 7vh var(--star-color), 31vw 159vh var(--star-color), 134vw 139vh var(--star-color), 163vw 137vh var(--star-color), 24vw 167vh var(--star-color), 40vw 20vh var(--star-color), 64vw 172vh var(--star-color), 134vw 94vh var(--star-color), 128vw 157vh var(--star-color), 15vw 195vh var(--star-color), 82vw 188vh var(--star-color), 116vw 113vh var(--star-color), 22vw 183vh var(--star-color), 59vw 2vh var(--star-color), 32vw 8vh var(--star-color), 38vw 20vh var(--star-color), 110vw 52vh var(--star-color), 64vw 64vh var(--star-color), 156vw 188vh var(--star-color), 176vw 67vh var(--star-color), 189vw 163vh var(--star-color), 196vw 192vh var(--star-color), 163vw 180vh var(--star-color), 130vw 99vh var(--star-color), 11vw 45vh var(--star-color), 62vw 102vh var(--star-color), 140vw 4vh var(--star-color), 56vw 21vh var(--star-color), 122vw 138vh var(--star-color), 129vw 172vh var(--star-color), 187vw 143vh var(--star-color), 2vw 129vh var(--star-color), 177vw 46vh var(--star-color), 3vw 164vh var(--star-color), 130vw 108vh var(--star-color), 101vw 191vh var(--star-color), 15vw 35vh var(--star-color), 195vw 198vh var(--star-color), 186vw 92vh var(--star-color), 111vw 38vh var(--star-color), 102vw 78vh var(--star-color), 43vw 72vh var(--star-color), 25vw 123vh var(--star-color), 103vw 107vh var(--star-color), 41vw 60vh var(--star-color), 96vw 182vh var(--star-color), 160vw 145vh var(--star-color), 5vw 169vh var(--star-color), 33vw 121vh var(--star-color), 87vw 167vh var(--star-color), 196vw 76vh var(--star-color), 154vw 50vh var(--star-color), 9vw 160vh var(--star-color), 197vw 106vh var(--star-color), 196vw 180vh var(--star-color), 61vw 77vh var(--star-color), 77vw 104vh var(--star-color), 152vw 172vh var(--star-color), 46vw 176vh var(--star-color), 84vw 100vh var(--star-color), 60vw 134vh var(--star-color), 135vw 70vh var(--star-color), 89vw 122vh var(--star-color), 27vw 175vh var(--star-color), 19vw 126vh var(--star-color), 142vw 127vh var(--star-color), 78vw 76vh var(--star-color), 76vw 135vh var(--star-color), 50vw 162vh var(--star-color), 116vw 177vh var(--star-color), 194vw 199vh var(--star-color), 104vw 45vh var(--star-color), 96vw 150vh var(--star-color), 171vw 153vh var(--star-color), 150vw 22vh var(--star-color), 131vw 192vh var(--star-color), 47vw 152vh var(--star-color), 166vw 171vh var(--star-color), 8vw 157vh var(--star-color), 7vw 24vh var(--star-color), 151vw 74vh var(--star-color), 172vw 60vh var(--star-color), 188vw 129vh var(--star-color), 183vw 90vh var(--star-color), 77vw 127vh var(--star-color), 134vw 14vh var(--star-color), 87vw 87vh var(--star-color), 127vw 116vh var(--star-color), 33vw 37vh var(--star-color), 133vw 155vh var(--star-color), 89vw 22vh var(--star-color), 119vw 109vh var(--star-color), 96vw 15vh var(--star-color), 140vw 101vh var(--star-color), 152vw 60vh var(--star-color), 135vw 129vh var(--star-color), 111vw 86vh var(--star-color), 66vw 20vh var(--star-color), 183vw 199vh var(--star-color), 182vw 91vh var(--star-color), 168vw 35vh var(--star-color), 139vw 109vh var(--star-color), 45vw 90vh var(--star-color), 131vw 66vh var(--star-color), 63vw 46vh var(--star-color), 133vw 120vh var(--star-color), 27vw 162vh var(--star-color), 116vw 74vh var(--star-color), 126vw 6vh var(--star-color), 170vw 57vh var(--star-color), 163vw 61vh var(--star-color), 140vw 104vh var(--star-color), 164vw 163vh var(--star-color), 69vw 143vh var(--star-color), 149vw 52vh var(--star-color), 52vw 83vh var(--star-color), 41vw 178vh var(--star-color), 62vw 80vh var(--star-color), 192vw 137vh var(--star-color), 37vw 52vh var(--star-color), 75vw 112vh var(--star-color), 113vw 38vh var(--star-color), 79vw 43vh var(--star-color), 168vw 195vh var(--star-color), 191vw 170vh var(--star-color), 32vw 107vh var(--star-color), 126vw 53vh var(--star-color), 56vw 174vh var(--star-color), 83vw 145vh var(--star-color), 41vw 150vh var(--star-color), 69vw 48vh var(--star-color), 6vw 26vh var(--star-color), 186vw 188vh var(--star-color), 78vw 196vh var(--star-color), 103vw 115vh var(--star-color), 19vw 4vh var(--star-color), 115vw 7vh var(--star-color), 186vw 136vh var(--star-color), 147vw 147vh var(--star-color), 79vw 8vh var(--star-color), 156vw 18vh var(--star-color), 70vw 63vh var(--star-color), 172vw 42vh var(--star-color), 98vw 105vh var(--star-color), 122vw 13vh var(--star-color), 176vw 162vh var(--star-color), 45vw 114vh var(--star-color), 88vw 51vh var(--star-color), 160vw 102vh var(--star-color), 52vw 114vh var(--star-color), 198vw 126vh var(--star-color), 45vw 98vh var(--star-color), 24vw 103vh var(--star-color), 44vw 38vh var(--star-color), 120vw 10vh var(--star-color), 156vw 50vh var(--star-color), 13vw 165vh var(--star-color), 16vw 14vh var(--star-color), 198vw 122vh var(--star-color), 66vw 67vh var(--star-color), 110vw 184vh var(--star-color), 76vw 186vh var(--star-color), 11vw 32vh var(--star-color), 191vw 188vh var(--star-color), 149vw 51vh var(--star-color), 188vw 135vh var(--star-color), 18vw 58vh var(--star-color), 61vw 76vh var(--star-color), 41vw 63vh var(--star-color), 127vw 29vh var(--star-color), 12vw 73vh var(--star-color), 62vw 4vh var(--star-color), 16vw 17vh var(--star-color), 154vw 117vh var(--star-color), 20vw 139vh var(--star-color), 128vw 100vh var(--star-color), 93vw 93vh var(--star-color), 187vw 106vh var(--star-color), 132vw 75vh var(--star-color), 95vw 152vh var(--star-color), 189vw 57vh var(--star-color), 97vw 22vh var(--star-color), 196vw 5vh var(--star-color), 199vw 53vh var(--star-color), 64vw 140vh var(--star-color), 27vw 55vh var(--star-color), 63vw 23vh var(--star-color), 154vw 40vh var(--star-color), 184vw 49vh var(--star-color), 113vw 117vh var(--star-color), 48vw 148vh var(--star-color), 59vw 173vh var(--star-color), 3vw 17vh var(--star-color), 150vw 152vh var(--star-color), 54vw 93vh var(--star-color), 26vw 61vh var(--star-color), 165vw 85vh var(--star-color), 91vw 34vh var(--star-color), 138vw 23vh var(--star-color), 65vw 86vh var(--star-color), 49vw 184vh var(--star-color), 53vw 72vh var(--star-color), 173vw 92vh var(--star-color), 105vw 24vh var(--star-color), 111vw 127vh var(--star-color), 154vw 14vh var(--star-color), 33vw 85vh var(--star-color), 87vw 143vh var(--star-color), 86vw 113vh var(--star-color), 173vw 167vh var(--star-color), 83vw 140vh var(--star-color), 165vw 121vh var(--star-color), 44vw 136vh var(--star-color), 57vw 104vh var(--star-color), 62vw 87vh var(--star-color), 110vw 91vh var(--star-color), 22vw 191vh var(--star-color), 141vw 138vh var(--star-color), 124vw 7vh var(--star-color), 37vw 136vh var(--star-color), 121vw 23vh var(--star-color), 131vw 147vh var(--star-color), 142vw 108vh var(--star-color), 182vw 200vh var(--star-color), 36vw 85vh var(--star-color), 103vw 48vh var(--star-color), 144vw 125vh var(--star-color), 104vw 116vh var(--star-color), 126vw 80vh var(--star-color), 8vw 50vh var(--star-color), 9vw 153vh var(--star-color), 134vw 96vh var(--star-color), 146vw 30vh var(--star-color), 51vw 31vh var(--star-color), 30vw 88vh var(--star-color), 95vw 89vh var(--star-color), 170vw 39vh var(--star-color), 195vw 189vh var(--star-color), 5vw 141vh var(--star-color), 1vw 78vh var(--star-color), 141vw 178vh var(--star-color), 172vw 69vh var(--star-color), 3vw 12vh var(--star-color), 58vw 188vh var(--star-color), 159vw 69vh var(--star-color), 127vw 120vh var(--star-color), 44vw 69vh var(--star-color), 66vw 167vh var(--star-color), 149vw 40vh var(--star-color), 15vw 185vh var(--star-color), 83vw 194vh var(--star-color), 182vw 29vh var(--star-color), 76vw 185vh var(--star-color), 187vw 176vh var(--star-color), 48vw 149vh var(--star-color), 28vw 49vh var(--star-color), 28vw 128vh var(--star-color), 131vw 95vh var(--star-color), 156vw 20vh var(--star-color), 159vw 114vh var(--star-color), 79vw 79vh var(--star-color), 27vw 76vh var(--star-color), 191vw 47vh var(--star-color), 137vw 143vh var(--star-color), 8vw 67vh var(--star-color), 100vw 191vh var(--star-color), 22vw 160vh var(--star-color), 50vw 73vh var(--star-color), 99vw 113vh var(--star-color), 66vw 11vh var(--star-color), 47vw 167vh var(--star-color), 89vw 134vh var(--star-color), 50vw 169vh var(--star-color), 153vw 82vh var(--star-color), 120vw 33vh var(--star-color), 73vw 25vh var(--star-color), 174vw 167vh var(--star-color), 143vw 151vh var(--star-color), 63vw 200vh var(--star-color), 43vw 21vh var(--star-color), 156vw 17vh var(--star-color), 157vw 20vh var(--star-color), 124vw 1vh var(--star-color), 70vw 40vh var(--star-color), 107vw 162vh var(--star-color), 55vw 115vh var(--star-color), 26vw 117vh var(--star-color), 147vw 59vh var(--star-color), 132vw 149vh var(--star-color), 90vw 75vh var(--star-color), 13vw 25vh var(--star-color), 49vw 189vh var(--star-color), 170vw 44vh var(--star-color), 137vw 149vh var(--star-color), 155vw 42vh var(--star-color), 52vw 195vh var(--star-color), 32vw 140vh var(--star-color), 3vw 126vh var(--star-color), 75vw 164vh var(--star-color), 22vw 15vh var(--star-color), 90vw 25vh var(--star-color), 45vw 106vh var(--star-color), 141vw 56vh var(--star-color), 30vw 138vh var(--star-color), 122vw 114vh var(--star-color), 66vw 106vh var(--star-color), 160vw 151vh var(--star-color), 61vw 75vh var(--star-color), 3vw 72vh var(--star-color), 186vw 95vh var(--star-color), 144vw 172vh var(--star-color), 39vw 6vh var(--star-color);
            animation-duration: var(--stars-sm-speed)
        }

        .stars-sm:after {
            animation-delay: calc(var(--stars-sm-speed) / -2)
        }

        .stars-md {
            width: 2px;
            height: 2px
        }

        .stars-md:before,
        .stars-md:after {
            box-shadow: 56vw 71vh var(--star-color), 112vw 28vh var(--star-color), 48vw 158vh var(--star-color), 159vw 84vh var(--star-color), 125vw 138vh var(--star-color), 103vw 121vh var(--star-color), 138vw 42vh var(--star-color), 30vw 69vh var(--star-color), 85vw 70vh var(--star-color), 37vw 196vh var(--star-color), 104vw 116vh var(--star-color), 33vw 45vh var(--star-color), 162vw 192vh var(--star-color), 192vw 51vh var(--star-color), 118vw 186vh var(--star-color), 65vw 112vh var(--star-color), 42vw 172vh var(--star-color), 139vw 46vh var(--star-color), 128vw 111vh var(--star-color), 95vw 161vh var(--star-color), 13vw 59vh var(--star-color), 33vw 50vh var(--star-color), 9vw 167vh var(--star-color), 5vw 67vh var(--star-color), 188vw 82vh var(--star-color), 8vw 72vh var(--star-color), 165vw 185vh var(--star-color), 108vw 126vh var(--star-color), 83vw 98vh var(--star-color), 153vw 41vh var(--star-color), 58vw 164vh var(--star-color), 84vw 117vh var(--star-color), 178vw 36vh var(--star-color), 11vw 115vh var(--star-color), 110vw 150vh var(--star-color), 11vw 1vh var(--star-color), 15vw 174vh var(--star-color), 67vw 183vh var(--star-color), 171vw 119vh var(--star-color), 158vw 95vh var(--star-color), 150vw 88vh var(--star-color), 146vw 141vh var(--star-color), 159vw 134vh var(--star-color), 108vw 47vh var(--star-color), 134vw 92vh var(--star-color), 191vw 37vh var(--star-color), 174vw 181vh var(--star-color), 104vw 42vh var(--star-color), 151vw 24vh var(--star-color), 179vw 37vh var(--star-color), 167vw 62vh var(--star-color), 196vw 103vh var(--star-color), 85vw 66vh var(--star-color), 160vw 145vh var(--star-color), 124vw 109vh var(--star-color), 155vw 86vh var(--star-color), 154vw 190vh var(--star-color), 113vw 153vh var(--star-color), 180vw 160vh var(--star-color), 171vw 24vh var(--star-color), 66vw 182vh var(--star-color), 149vw 26vh var(--star-color), 89vw 168vh var(--star-color), 59vw 87vh var(--star-color), 146vw 140vh var(--star-color), 184vw 99vh var(--star-color), 53vw 185vh var(--star-color), 120vw 41vh var(--star-color), 197vw 47vh var(--star-color), 121vw 77vh var(--star-color), 7vw 90vh var(--star-color), 89vw 187vh var(--star-color), 160vw 163vh var(--star-color), 144vw 78vh var(--star-color), 58vw 198vh var(--star-color), 3vw 36vh var(--star-color), 96vw 74vh var(--star-color), 65vw 188vh var(--star-color), 132vw 135vh var(--star-color), 173vw 121vh var(--star-color), 125vw 178vh var(--star-color), 65vw 179vh var(--star-color), 126vw 134vh var(--star-color), 12vw 140vh var(--star-color), 73vw 102vh var(--star-color), 122vw 190vh var(--star-color), 183vw 35vh var(--star-color), 125vw 77vh var(--star-color), 196vw 35vh var(--star-color), 29vw 153vh var(--star-color), 14vw 4vh var(--star-color), 161vw 33vh var(--star-color), 55vw 134vh var(--star-color), 115vw 194vh var(--star-color), 151vw 184vh var(--star-color), 124vw 170vh var(--star-color), 147vw 115vh var(--star-color), 165vw 33vh var(--star-color), 47vw 64vh var(--star-color), 132vw 196vh var(--star-color), 113vw 109vh var(--star-color), 82vw 108vh var(--star-color), 80vw 8vh var(--star-color), 112vw 162vh var(--star-color), 138vw 50vh var(--star-color), 21vw 37vh var(--star-color), 119vw 139vh var(--star-color), 59vw 140vh var(--star-color), 96vw 175vh var(--star-color), 12vw 159vh var(--star-color), 177vw 56vh var(--star-color), 180vw 96vh var(--star-color), 172vw 98vh var(--star-color), 185vw 53vh var(--star-color), 106vw 91vh var(--star-color), 27vw 61vh var(--star-color), 122vw 164vh var(--star-color), 73vw 5vh var(--star-color), 25vw 114vh var(--star-color), 167vw 92vh var(--star-color), 82vw 107vh var(--star-color), 60vw 128vh var(--star-color), 78vw 83vh var(--star-color), 192vw 117vh var(--star-color), 72vw 2vh var(--star-color), 85vw 135vh var(--star-color), 156vw 22vh var(--star-color), 26vw 137vh var(--star-color), 179vw 167vh var(--star-color), 90vw 104vh var(--star-color), 172vw 185vh var(--star-color), 123vw 68vh var(--star-color), 82vw 199vh var(--star-color), 63vw 2vh var(--star-color), 40vw 79vh var(--star-color), 127vw 21vh var(--star-color), 107vw 11vh var(--star-color), 125vw 46vh var(--star-color), 38vw 1vh var(--star-color), 68vw 78vh var(--star-color), 54vw 57vh var(--star-color), 142vw 187vh var(--star-color), 153vw 30vh var(--star-color), 180vw 73vh var(--star-color), 53vw 160vh var(--star-color), 96vw 164vh var(--star-color), 198vw 150vh var(--star-color), 67vw 47vh var(--star-color), 153vw 171vh var(--star-color), 148vw 200vh var(--star-color), 36vw 110vh var(--star-color), 5vw 68vh var(--star-color), 42vw 148vh var(--star-color), 81vw 182vh var(--star-color), 95vw 114vh var(--star-color), 26vw 19vh var(--star-color), 75vw 167vh var(--star-color), 179vw 128vh var(--star-color), 96vw 16vh var(--star-color), 133vw 102vh var(--star-color), 35vw 84vh var(--star-color), 179vw 142vh var(--star-color), 83vw 54vh var(--star-color), 42vw 146vh var(--star-color), 19vw 127vh var(--star-color), 37vw 1vh var(--star-color), 196vw 56vh var(--star-color), 175vw 175vh var(--star-color), 13vw 113vh var(--star-color), 85vw 71vh var(--star-color), 131vw 104vh var(--star-color), 182vw 9vh var(--star-color), 100vw 6vh var(--star-color), 54vw 32vh var(--star-color), 164vw 191vh var(--star-color), 103vw 70vh var(--star-color), 53vw 123vh var(--star-color), 178vw 192vh var(--star-color), 49vw 180vh var(--star-color), 41vw 36vh var(--star-color), 68vw 39vh var(--star-color), 103vw 77vh var(--star-color), 196vw 111vh var(--star-color), 98vw 127vh var(--star-color), 65vw 40vh var(--star-color), 158vw 34vh var(--star-color), 48vw 46vh var(--star-color), 80vw 85vh var(--star-color), 70vw 17vh var(--star-color), 110vw 29vh var(--star-color), 165vw 139vh var(--star-color), 44vw 51vh var(--star-color), 162vw 14vh var(--star-color), 153vw 135vh var(--star-color), 179vw 173vh var(--star-color), 94vw 16vh var(--star-color), 118vw 152vh var(--star-color), 24vw 132vh var(--star-color), 44vw 70vh var(--star-color), 37vw 170vh var(--star-color);
            animation-duration: var(--stars-md-speed)
        }

        .stars-md:after {
            animation-delay: calc(var(--stars-md-speed) / -2)
        }

        .stars-lg {
            width: 4px;
            height: 4px
        }

        .stars-lg:before,
        .stars-lg:after {
            box-shadow: 6vw 176vh var(--star-color), 2vw 135vh var(--star-color), 199vw 142vh var(--star-color), 24vw 91vh var(--star-color), 111vw 34vh var(--star-color), 112vw 107vh var(--star-color), 166vw 3vh var(--star-color), 114vw 74vh var(--star-color), 188vw 42vh var(--star-color), 10vw 68vh var(--star-color), 136vw 75vh var(--star-color), 166vw 170vh var(--star-color), 200vw 2vh var(--star-color), 73vw 29vh var(--star-color), 123vw 83vh var(--star-color), 45vw 115vh var(--star-color), 171vw 133vh var(--star-color), 52vw 160vh var(--star-color), 120vw 8vh var(--star-color), 93vw 163vh var(--star-color), 96vw 39vh var(--star-color), 34vw 130vh var(--star-color), 186vw 31vh var(--star-color), 168vw 177vh var(--star-color), 151vw 49vh var(--star-color), 32vw 48vh var(--star-color), 181vw 6vh var(--star-color), 99vw 12vh var(--star-color), 142vw 92vh var(--star-color), 48vw 163vh var(--star-color), 44vw 55vh var(--star-color), 179vw 112vh var(--star-color), 84vw 120vh var(--star-color), 169vw 85vh var(--star-color), 181vw 117vh var(--star-color), 178vw 170vh var(--star-color), 59vw 155vh var(--star-color), 4vw 59vh var(--star-color), 79vw 84vh var(--star-color), 40vw 118vh var(--star-color), 143vw 162vh var(--star-color), 37vw 151vh var(--star-color), 6vw 175vh var(--star-color), 178vw 107vh var(--star-color), 180vw 80vh var(--star-color), 174vw 126vh var(--star-color), 104vw 197vh var(--star-color), 54vw 105vh var(--star-color), 107vw 119vh var(--star-color), 93vw 47vh var(--star-color);
            animation-duration: var(--stars-lg-speed)
        }

        .stars-lg:after {
            animation-delay: calc(var(--stars-lg-speed) / -2)
        }

        @keyframes stars {
            0% {
                opacity: 0
            }

            20% {
                opacity: 1
            }

            80% {
                opacity: 1
            }

            100% {
                opacity: 0;
                transform: translateY(0)
            }
        }

        @keyframes exhaust {
            to {
                transform: scaleX(0.98) translateY(-1vmin)
            }
        }

        @keyframes speed-up-exhaust {
            from {
                transform: scale(0.98, 1)
            }

            to {
                transform: scale(0.96, 1.5)
            }
        }

        @keyframes speed-up-ship {
            from {
                transform: translateY(-5%)
            }

            to {
                transform: translateY(-3%)
            }
        }
    </style>
</head>

<body>

    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="hover-area"></div>
    <div class="star-field">
        <div class="stars stars-sm"></div>
        <div class="stars stars-md"></div>
        <div class="stars stars-lg"></div>
    </div>
    <div class="ship">
        <div class="wrapper">
            <div class="body side left"></div>
            <div class="body main">
                <div class="wing left"></div>
                <div class="wing right"></div>
                <div class="booster"></div>
                <div class="exhaust"></div>
            </div>
            <div class="body side right"></div>
        </div>
    </div>
    <script type="text/javascript">
        //3秒钟之后跳转到指定的页面
        setTimeout(() => {
            window.location.href = '<?= addslashes($location) ?>';
        }, 3000);
    </script>
</body>

</html>