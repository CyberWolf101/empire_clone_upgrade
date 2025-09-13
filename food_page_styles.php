<style>
    .ter {
        background-color: #fff;
        padding: 0 10px;
    }

    .check {
        padding: 2%;
        font-size: 12px;
        width: 25%;
    }

    .check span {
        font-size: 13px;
        font-weight: 600;
    }

    .img {
        max-width: 50%;
        max-height: 50%;
        border-radius: 50%;
        cursor: pointer;
    }

    .btn-buya {
        display: inline-block;
        padding: 6px !important;
        border: none;
        color: #fff;
        font-size: 10px !important;
        text-transform: uppercase;
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        transition: 0.3s;
        background: #FEBF01;
        margin: 4px;
    }

    .btn-buya:hover {
        font-size: 12px !important;
        font-weight: 800;
        background: #000;
    }

    .form-control {
        height: 40px;
        border-radius: none !important;
    }

    .section-title h2::after {
        content: "";
        position: absolute;
        display: block;
        width: 80px;
        background: none;
        bottom: 0;
        left: calc(2% - 25px);
    }

    .box {
        border-radius: 0px;
    }

    .pricing .box {
        /* padding: 20px 0 0; */
        background: #f8f8f8;
        text-align: center;
        box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.12);
        border-radius: 0px;
        position: relative;
        overflow: hidden;
    }

    .arrow {
        animation: point 1s linear infinite;
    }

    @keyframes point {
        from {
            transform: translate(0px);
        }

        to {
            transform: translate(5px);
        }
    }

    /* Search Bar and Suggestions Styles */
    .search-container {
        position: relative;
        margin-bottom: 20px;
        width: 100%;
        max-width: 500px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-input {
        flex-grow: 1;
        padding: 10px;
        font-size: 14px;
        border: 2px solid #FEBF01;
        border-radius: 5px;
        outline: none;
    }

    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .search-suggestions div {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .search-suggestions div:hover {
        background: #f0f0f0;
    }

    .search-suggestions div:last-child {
        border-bottom: none;
    }

    .btn-reset {
        padding: 6px 12px;
        border: 2px solid #FEBF01;
        background: #fff;
        color: #FEBF01 !important;
        font-size: 10px;
        text-transform: uppercase;
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        transition: 0.3s;
        border-radius: 5px;
    }

    .btn-reset:hover {
        background: #FEBF01;
        color: #fff !important;
    }

    .go_home {
        background: #FEBF01;
        color: #fff !important;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        font-size: 23px;
    }
</style>