window.onload = () => {
    const FiltersForm = document.querySelector("#ajax");

    document.querySelector("#ajax input").addEventListener("change", () => {
        const Form = new FormData(FiltersForm);

        const Params = new URLSearchParams();

        Form.forEach((value, key) => {
            Params.append(key, value);
        });
        const Url = new URL(window.location.href);

        fetch(`${Url.pathname}?${Params.toString()}&ajax=1`,{
            headers: {
                "X-Requested-width": "XMLHttpRequest"
            }
        }).then(response =>
            response.json()
        ).then(data => {
            document.querySelector("#ajax-content").innerHTML = data.content;

            history.pushState({}, null, Url.pathname + "?" + Params.toString())
        }).catch(e => alert(e))
    ;})
}